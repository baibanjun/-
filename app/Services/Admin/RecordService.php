<?php
namespace App\Services\Admin;

use Illuminate\Support\Facades\Redis;

/**
 * 监听记录操作日志
 */

class RecordService 
{
	/**
	 * 创建操作记录日志
	 * @param array $data
	 */
	static public function createLog($action, $data)
	{
	    $data = $data[0];
	    $adminId = auth('admin')->user()?auth('admin')->user()->id:0;
	    $logData['admin_id']        = $adminId;
		$logData['operation_table'] = $data->getTable();	
		$logData['object_id']       = $data->getKey();
		$logData['action_type']     = $action['type'];
		$logData['created_at']      = date('Y-m-d H:i:s');
		$connect                    = $data->getConnectionName();
		
		if ($connect != null && $connect != 'mysql')
		{
			return 'not mysql';
		}
		
		if ($action['type'] == 'created'){
			
			$logData['new_data'] = $data->getAttributes();//json_encode($data->getAttributes());
			
			$logData['old_data']    = NULL;
			$logData['differ_data'] = NULL;
		}
		
		if ($action['type'] == 'updated'){
			
		    $oldData = $data->getOriginal();
		    $newData = $data->getAttributes();
		    
		    if (isset($oldData['updated_at'])){
		    	unset($oldData['updated_at']);
		    }
		    
		    if (isset($newData['updated_at'])){
		    	unset($newData['updated_at']);
		    }
		    
			$differData['new'] = array_diff_assoc($newData,$oldData);	
			$differData['old'] = array_diff_assoc($oldData,$newData);
			
			$logData['old_data']    = $oldData;//json_encode($oldData);
			$logData['new_data']    = $newData;//json_encode($newData);
			$logData['differ_data'] = $differData;//json_encode($differData);
		}
		
		if ($action['type'] == 'deleted'){
			
			$logData['old_data'] = $data->getOriginal();//json_encode($data->getOriginal());
			
			$logData['new_data']    = NULL;
			$logData['differ_data'] = NULL;
		}
		
		$jsonData = json_encode($logData);
		Redis::select(5);
		$resul = Redis::LPUSH('admin_logs:operation_logs:'.$logData['operation_table'],$jsonData);
	}
}