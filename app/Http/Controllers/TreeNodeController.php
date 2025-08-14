<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Corporation;
use App\Models\Building;
use App\Models\Property;
use App\Models\TenancyPeriod;
use App\Models\Tenant;
use Illuminate\Routing\Controller;

class TreeNodeController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:sanctum');
	}

	// Get all direct children of a given node
	public function children($type, $id)
	{
		switch (strtolower($type)) {
			case 'corporation':
				$children = Building::where('corporation_id', $id)->get();
				break;
			case 'building':
				$children = Property::where('building_id', $id)->get();
				break;
			case 'property':
				$children = TenancyPeriod::where('property_id', $id)->get();
				break;
			case 'tenancyperiod':
			case 'tenancy_period':
				$children = Tenant::where('tenancy_period_id', $id)->get();
				break;
			default:
				return response()->json(['error' => 'Invalid node type'], 400);
		}
		return response()->json(['children' => $children], 200);
	}

	// Change the parent node of a given node
	public function move(Request $request, $type, $id)
	{
		$request->validate([
			'new_parent_id' => 'required|integer',
		]);
		$newParentId = $request->new_parent_id;
		switch (strtolower($type)) {
			case 'building':
				$building = Building::findOrFail($id);
				$corporation = Corporation::findOrFail($newParentId);
				$building->corporation_id = $corporation->id;
				$building->height = $corporation->height + 1;
				$building->save();
				return response()->json(['status' => 'success', 'data' => $building], 200);
			case 'property':
				$property = Property::findOrFail($id);
				$building = Building::findOrFail($newParentId);
				$property->building_id = $building->id;
				$property->height = $building->height + 1;
				$property->save();
				return response()->json(['status' => 'success', 'data' => $property], 200);
			case 'tenancyperiod':
			case 'tenancy_period':
				$tenancyPeriod = TenancyPeriod::findOrFail($id);
				$property = Property::findOrFail($newParentId);
				// Enforce only one active tenancy period per property
				if ($tenancyPeriod->active) {
					$activeCount = TenancyPeriod::where('property_id', $property->id)->where('active', true)->where('id', '!=', $tenancyPeriod->id)->count();
					if ($activeCount > 0) {
						return response()->json(['error' => 'Only one active tenancy period allowed per property'], 400);
					}
				}
				$tenancyPeriod->property_id = $property->id;
				$tenancyPeriod->height = $property->height + 1;
				$tenancyPeriod->save();
				return response()->json(['status' => 'success', 'data' => $tenancyPeriod], 200);
			case 'tenant':
				$tenant = Tenant::findOrFail($id);
				$tenancyPeriod = TenancyPeriod::findOrFail($newParentId);
				// Enforce max 4 tenants per tenancy period
				$tenantCount = Tenant::where('tenancy_period_id', $tenancyPeriod->id)->count();
				if ($tenantCount >= 4) {
					return response()->json(['error' => 'A tenancy period can have a maximum of 4 tenants'], 400);
				}
				$tenant->tenancy_period_id = $tenancyPeriod->id;
				$tenant->height = $tenancyPeriod->height + 1;
				$tenant->save();
				return response()->json(['status' => 'success', 'data' => $tenant], 200);
			default:
				return response()->json(['error' => 'Invalid node type'], 400);
		}
	}
}
