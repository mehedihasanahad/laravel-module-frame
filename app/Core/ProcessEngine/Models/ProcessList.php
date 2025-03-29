<?php

namespace App\Core\ProcessEngine\Models;

use App\Core\ProcessEngine\ProcessHelper;
use App\Libraries\CommonFunction;
use App\Traits\CreatedByUpdatedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProcessList extends Model
{
    use CreatedByUpdatedBy;
    protected $table = 'process_lists';
    protected $primaryKey = 'id';
    private static int $process_type_id;
    private static int $status_id;
    private static string $desk;

    protected $fillable = [
        'process_type_id',
        'org_id',
        'ref_id',
        'tracking_no',
        'json_object',
        'process_user_desk_id',
        'process_status_id',
        'user_id',
        'read_status',
        'remarks',
        'locked_at',
        'locked_by',
        'closed_by',
        'previous_hash',
        'hash_value',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    /**
     * Retrieves the base query builder object.
     *
     * This function returns the base query builder object that can be used to build the query.
     *
     * @return QueryBuilder The base query builder object
     */
    private static function getQuery()
    {
        return ProcessList::leftJoin('process_user_desks', 'process_lists.process_user_desk_id', '=', 'process_user_desks.id')
            ->join('process_statuses', function ($on) {
                $on->on('process_lists.process_status_id', '=', 'process_statuses.id')
                    ->on('process_lists.process_type_id', '=', 'process_statuses.process_type_id');
            })
            ->join('process_types', 'process_lists.process_type_id', '=', 'process_types.id')
            ->when(self::$process_type_id > 0,function($query){
                $query->where('process_lists.process_type_id', self::$process_type_id);
            });
    }

    /**
     * Retrieve a list of data based on process type ID, status, request, and desk.
     *
     * This function retrieves a list of data based on the provided process type ID, status, request, and desk.
     *
     * @param int $process_type_id
     * @param int $status
     * @param Request $request
     * @param string $desk
     * @return mixed
     */
    public static function listData(int $process_type_id, int $status, Request $request, string $desk):mixed
    {
        # Set the class properties
        self::$process_type_id = $process_type_id;
        self::$status_id = $status;
        self::$desk = $desk;

        # Retrieve the required permissions for the given process type
        $requiredPermissions = ProcessType::getPermissions($process_type_id);

        $query = self::getQuery()
            ->when(!Auth::user()->canAny($requiredPermissions),function($query){
                 $query->whereProcessStatusId(0);
            })
            ->orderBy('process_lists.created_at', 'desc');

        # Apply user type filter to the query
        $query = self::applyUserTypeFilter($query);

        # Apply favorite list filter if the desk is 'favorite_list'
        if ($desk == 'favorite-list') {
            $query = self::applyFavoriteListFilter($query);
        }

        # Check if 'process_search' parameter exists in the request
        if ($request->has('process_search')) {

            # This permission is assigned to the "Desk user"
            if (Auth::user()->can('filter-office-ids')) {
                $query = self::applyOfficeIdsFilter($query);
            }

            # This permission is assigned to the "Desk user" & "System Admin"
            if (Auth::user()->can('access-list-search')) {
                $query->whereNotIn('process_lists.process_status_id', [-1]);
            }

            # Apply search filter based on the request
            $query = self::searchData($query,$request);
        } else {
            # Apply date range filter if 'process_search' parameter is not present
            $query = self::applyDateRangeFilter($query);
        }

        # Select specific columns from the query result
        return $query->select([
            'process_lists.id',
            'process_lists.ref_id',
            'process_lists.tracking_no',
            'json_object',
            'process_lists.process_user_desk_id',
            'process_lists.process_type_id',
            'process_lists.process_status_id',
            'process_lists.updated_at',
            'process_lists.locked_by',
            'process_lists.created_by',
            'process_lists.locked_at',
            'process_user_desks.name as desk_name',
            'process_statuses.status_name',
            'process_types.name as process_name'
        ]);
    }

    /**
     * Applies a filter to the query based on user type.
     *
     * This function modifies the $query object to add the necessary filter conditions
     *
     * @param  QueryBuilder $query The query builder object
     * @return QueryBuilder        The modified query builder object
     */
    private static function applyUserTypeFilter($query)
    {
        if (Auth::user()->can('process-lists-view-all')) {  # This permission is assigned to the "System Admin"
            $query->whereNotIn('process_lists.process_status_id', [-1]);
        } elseif (Auth::user()->can('process-lists-view-by-id')) {  # This permission is assigned to the "Applicant"
            $listFilterBy = config('engine.applicationListFilterBy','user');
            if ($listFilterBy == "user"){
                $query->where('process_lists.created_by', Auth::user()->id);
            }elseif ($listFilterBy == "organization") {
                $organizationId = ProcessHelper::getOrganizationId();
                $query->where('process_lists.org_id', $organizationId);
            }

        } else {  # Apply desk filter for "Desk User"
            $query = self::applyDeskFilter($query);
        }
        return $query;
    }

    /**
     * Applies a filter to the query based on favorite list.
     *
     * This function modifies the $query object to add the necessary filter conditions
     *
     * @param  QueryBuilder $query The query builder object
     * @return QueryBuilder        The modified query builder object
     */
    private static function applyFavoriteListFilter($query)
    {
        $query->join('process_favorite_lists', 'process_lists.id', '=', 'process_favorite_lists.process_list_id')
            ->where('process_favorite_lists.user_id', ProcessHelper::getUserId());
        return $query;
    }

    /**
     * Applies a filter to the query based on desk.
     *
     * This function modifies the $query object to add the necessary filter conditions
     *
     * @param  QueryBuilder $query The query builder object
     * @return QueryBuilder        The modified query builder object
     */
    private static function applyDeskFilter($query)
    {
        $userDeskIds = ProcessHelper::getUserDeskIds();
        $user_id = Auth::id();
        $delegatedUserDeskOfficeIds = ProcessHelper::getDelegatedUserDeskIds();
        if (self::$desk == 'my-desk') {
            $query->where(function ($query1) use ($userDeskIds, $user_id) {
                $query1->whereIn('process_lists.process_user_desk_id', $userDeskIds)
                    ->where(function ($query2) use ($user_id) {
                        $query2->where('process_lists.user_id', $user_id)
                            ->orWhere('process_lists.user_id', 0);
                    })
                    ->where('process_lists.process_user_desk_id', '!=', 0)
                    ->whereNotIn('process_lists.process_status_id', [-1]);
            });
        } else if (self::$desk == 'my-delegation-desk') {
            $query->where(function ($query) use ($delegatedUserDeskOfficeIds) {
                if (empty($delegatedUserDeskOfficeIds)) {
                    $query->where('process_lists.process_user_desk_id', 5555);
                } else {
                    $query->where(function ($query) use ($delegatedUserDeskOfficeIds) {
                        foreach ($delegatedUserDeskOfficeIds as $data) {
                            $query->orWhere(function ($query) use ($data) {
                                $query->whereIn('process_lists.process_user_desk_id', $data['desk_ids'])
                                    ->where(function ($query) use ($data) {
                                        $query->where('process_lists.user_id', $data['user_id'])
                                            ->orWhere('process_lists.user_id', 0);
                                    })
                                    ->where('process_lists.process_user_desk_id', '!=', 0)
                                    ->whereNotIn('process_lists.process_status_id', [-1]);
                            });
                        }
                    });
                }
            });

        }
        return $query;
    }

    /**
     * Applies a filter to the query based on date range.
     *
     * This function modifies the $query object to add the necessary filter conditions
     *
     * @param  QueryBuilder $query The query builder object
     * @return QueryBuilder        The modified query builder object
     */
    private static function applyDateRangeFilter($query)
    {
        $from = Carbon::now();
        $to = Carbon::now();
        $previous_month = (in_array(ProcessHelper::getUserType(), ['5x505', '6x606']) ? 36 : 6);
        $from->subMonths($previous_month); //maximum 5month data selection by default
        $query->whereBetween('process_lists.created_at', [$from, $to]);
        return $query;
    }

    /**
     * Applies a filter to the query based on office IDs.
     *
     * This function modifies the $query object to add the necessary filter conditions
     *
     * @param  QueryBuilder $query The query builder object
     * @return QueryBuilder        The modified query builder object
     */
    private static function applyOfficeIdsFilter($query)
    {
        $getSelfAndDelegatedUserDeskOfficeIds = ProcessHelper::getDelegatedUserDeskIds();
        $query->where(function ($query1) use ($getSelfAndDelegatedUserDeskOfficeIds) {
            $i = 0;
            foreach ($getSelfAndDelegatedUserDeskOfficeIds as $data) {
                if ($i == 0) {
                    $query1->where(function ($queryInc) use ($data) {
                        $queryInc->whereIn('process_lists.office_id', $data['office_ids']);
                    });
                } else {
                    $query1->orWhere(function ($queryInc) use ($data) {
                        $queryInc->whereIn('process_lists.office_id', $data['office_ids']);
                    });
                }
                $i++;
            }
        });
        return $query;
    }

    public static function searchData($query, $request)
    {
        $searchDate = $request->get('search_date');
        $searchText = $request->get('search_text', '');
        $statusId   = $request->get('search_status', 0);
        $searchTime = $request->get('search_time');

        if (!empty($searchDate)) {
            $from = Carbon::parse($searchDate);
            $to = Carbon::parse($searchDate);
        } else {
            $from = Carbon::now();
            $to = Carbon::now();
        }
        switch ($searchTime) {
            case 30:
                $from->subMonth();
                $to->addMonth();
                break;
            case 15:
                $from->subWeeks(2);
                $to->addWeeks(2);
                break;
            case 7:
                $from->subWeek();
                $to->addWeek();
                break;
            case 1:
                $from->subDay();
                $to->addDay();
                break;
            default:
        }
        # Include date-time wise search
        if (!empty($searchDate)) {
            $query->whereBetween('process_lists.created_at', [$from, $to]);
        }

        # Check if search text length is greater than 2
        if (!empty($searchText) && strlen($searchText) > 2) {
            $query->where(function ($query1) use ($searchText) {
                $query1->where('process_lists.json_object', 'like', '%' . $searchText . '%')
                       ->orWhere('process_lists.tracking_no', 'like', '%' . $searchText . '%');
            });
        }

        # Check if the current user has the permission to access draft application list show
        if ($statusId > 0) {
            $query->where('process_lists.process_status_id', $statusId);
        }

        return $query;
    }
}

