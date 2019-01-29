<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 达人
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class UserTalent extends Model
{

    /**
     * 正常(通过)
     * 
     * @var integer
     */
    const STATUS_1 = 1;

    /**
     * 冻结
     * 
     * @var integer
     */
    const STATUS_2 = 2;

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'name',
        'mobile',
        'team',
        'team_pic',
        'status'
    ];

    protected $casts = [
        'team_pic' => 'json'
    ];

    /**
     * 关系用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'uid');
    }

    /**
     * 关联账户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\UserAccount', 'uid');
    }
}
