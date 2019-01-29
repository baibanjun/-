<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTeamMember extends Model
{
    const STATUS_NORMAL = 0;
    
    const STATUS_FREEZE = 1;
    
    protected $guarded = [];
    
    /**
     * 关联成员
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function memberUser()
    {
        return $this->belongsTo('App\Models\User', 'team_member_uid');
    }
    
    /**
     * 关联达人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function talent()
    {
        return $this->belongsTo('App\Models\UserTalent', 'captain_uid', 'uid');
    }
}
