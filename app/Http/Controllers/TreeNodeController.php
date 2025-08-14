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
	// Node type constants
	private const TYPE_CORPORATION = 'corporation';
	private const TYPE_BUILDING = 'building';
	private const TYPE_PROPERTY = 'property';
	private const TYPE_TENANCY_PERIOD = 'tenancyperiod';
	private const TYPE_TENANCY_PERIOD_ALT = 'tenancy_period';
	private const TYPE_TENANT = 'tenant';

	public function __construct()
	{
		$this->middleware('auth:sanctum');
	}

	/**
	 * Get all direct children of a given node (one layer only).
	 *
	 * @param string $type
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function children($type, $id)
	{
		$type = strtolower($type);
		$children = $this->getChildrenByType($type, $id);
		if ($children === null) {
			return response()->json(['error' => 'Invalid node type'], 400);
		}
		return response()->json(['children' => $children], 200);
	}

	/**
	 * Change the parent node of a given node, enforcing business rules.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param string $type
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function move(Request $request, $type, $id)
	{
		$request->validate([
			'new_parent_id' => 'required|integer',
		]);
		$type = strtolower($type);
		$newParentId = $request->new_parent_id;

		switch ($type) {
			case self::TYPE_BUILDING:
				return $this->moveBuilding($id, $newParentId);
			case self::TYPE_PROPERTY:
				return $this->moveProperty($id, $newParentId);
			case self::TYPE_TENANCY_PERIOD:
			case self::TYPE_TENANCY_PERIOD_ALT:
				return $this->moveTenancyPeriod($id, $newParentId);
			case self::TYPE_TENANT:
				return $this->moveTenant($id, $newParentId);
			default:
				return response()->json(['error' => 'Invalid node type'], 400);
		}
	}

	/**
	 * Get children by node type.
	 */
	private function getChildrenByType($type, $id)
	{
		switch ($type) {
			case self::TYPE_CORPORATION:
				return Building::where('corporation_id', $id)->get();
			case self::TYPE_BUILDING:
				return Property::where('building_id', $id)->get();
			case self::TYPE_PROPERTY:
				return TenancyPeriod::where('property_id', $id)->get();
			case self::TYPE_TENANCY_PERIOD:
			case self::TYPE_TENANCY_PERIOD_ALT:
				return Tenant::where('tenancy_period_id', $id)->get();
			default:
				return null;
		}
	}

	/**
	 * Move a building to a new corporation.
	 */
	private function moveBuilding($buildingId, $corporationId)
	{
		$building = Building::findOrFail($buildingId);
		$corporation = Corporation::findOrFail($corporationId);
		$building->corporation_id = $corporation->id;
		$building->height = $corporation->height + 1;
		$building->save();
		return response()->json(['status' => 'success', 'data' => $building], 200);
	}

	/**
	 * Move a property to a new building.
	 */
	private function moveProperty($propertyId, $buildingId)
	{
		$property = Property::findOrFail($propertyId);
		$building = Building::findOrFail($buildingId);
		$property->building_id = $building->id;
		$property->height = $building->height + 1;
		$property->save();
		return response()->json(['status' => 'success', 'data' => $property], 200);
	}

	/**
	 * Move a tenancy period to a new property, enforcing business rules.
	 */
	private function moveTenancyPeriod($tenancyPeriodId, $propertyId)
	{
		$tenancyPeriod = TenancyPeriod::findOrFail($tenancyPeriodId);
		$property = Property::findOrFail($propertyId);
		// Enforce only one active tenancy period per property
		if ($tenancyPeriod->active) {
			$activeCount = TenancyPeriod::where('property_id', $property->id)
				->where('active', true)
				->where('id', '!=', $tenancyPeriod->id)
				->count();
			if ($activeCount > 0) {
				return response()->json(['error' => 'Only one active tenancy period allowed per property'], 400);
			}
		}
		$tenancyPeriod->property_id = $property->id;
		$tenancyPeriod->height = $property->height + 1;
		$tenancyPeriod->save();
		return response()->json(['status' => 'success', 'data' => $tenancyPeriod], 200);
	}

	/**
	 * Move a tenant to a new tenancy period, enforcing business rules.
	 */
	private function moveTenant($tenantId, $tenancyPeriodId)
	{
		$tenant = Tenant::findOrFail($tenantId);
		$tenancyPeriod = TenancyPeriod::findOrFail($tenancyPeriodId);
		// Enforce max 4 tenants per tenancy period
		$tenantCount = Tenant::where('tenancy_period_id', $tenancyPeriod->id)->count();
		if ($tenantCount >= 4) {
			return response()->json(['error' => 'A tenancy period can have a maximum of 4 tenants'], 400);
		}
		$tenant->tenancy_period_id = $tenancyPeriod->id;
		$tenant->height = $tenancyPeriod->height + 1;
		$tenant->save();
		return response()->json(['status' => 'success', 'data' => $tenant], 200);
	}
}
