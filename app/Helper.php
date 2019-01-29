<?php

use Illuminate\Support\Facades\Log;

function statics ($file)
{
    return config('console.static_url').$file.'?v='.config('console.static_v');
}

/**
 * 截取REQUEST_URI
 * @param string $URL	REQUEST_URI
 */
function REQUEST_URI($URL,$ARR){
    $U = explode("?", $URL);
    $B = false;
    foreach ($ARR as $key => $value) {
        if($value === $U[0]){
            $B = true;
        }
    }
    return $B;
}

/**
 * 统一格式输出日志
 *
 * @param string    $typeName   类别名
 * @param array     $log        日志详情
 * @param integer   $startTime  开始时间
 */
function setLog($typeName, $startTime = 0, $log = [])
{
    $diffTime = $startTime ? getDiffMicrotime($startTime) : '0.00';
    
    Log::info($typeName . ' 参数:' . json_encode($log) . '执行用时:' . $diffTime);
}

/**
 * 计算时间差
 * @param integer $sTime
 */
function getDiffMicrotime($sTime) {
    $sTime = explode(' ', $sTime);
    $mTime = explode(' ',microtime());
    return roundDown((($mTime[1]+$mTime[0]) - ($sTime[1]+$sTime[0])),3);
}

/**
 * 向下啥去为最为接近的小数
 * @param number $x		操作对象
 * @param number $prec	小数点后几位
 */
function roundDown($x, $prec=2)
{
    return substr(sprintf("%.8f", $x), 0, -(8-$prec));
}

/**
 * 电话号码中间四位打星
 *
 * @param string $phone
 * @return string
 */
function hideTel($phone){
    $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
    if($IsWhat == 1){
        return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
    }else{
        return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
    }
}

/**
 * 姓名,姓之后都打星
 *
 * @param string $name
 * @param number $num
 * @return string
 */
function starReplace($name, $num = 0)
{
    if ($num && mb_strlen($name, 'UTF-8') > $num) {
        return mb_substr($name, 0, 4) . '*';
    }
    
    if ($num && mb_strlen($name, 'UTF-8') <= $num) {
        return $name;
    }
    
    $doubleSurname = [
        '欧阳', '太史', '端木', '上官', '司马', '东方', '独孤', '南宫',
        '万俟', '闻人', '夏侯', '诸葛', '尉迟', '公羊', '赫连', '澹台', '皇甫', '宗政', '濮阳',
        '公冶', '太叔', '申屠', '公孙', '慕容', '仲孙', '钟离', '长孙', '宇文', '司徒', '鲜于',
        '司空', '闾丘', '子车', '亓官', '司寇', '巫马', '公西', '颛孙', '壤驷', '公良', '漆雕', '乐正',
        '宰父', '谷梁', '拓跋', '夹谷', '轩辕', '令狐', '段干', '百里', '呼延', '东郭', '南门', '羊舌',
        '微生', '公户', '公玉', '公仪', '梁丘', '公仲', '公上', '公门', '公山', '公坚', '左丘', '公伯',
        '西门', '公祖', '第五', '公乘', '贯丘', '公皙', '南荣', '东里', '东宫', '仲长', '子书', '子桑',
        '即墨', '达奚', '褚师', '吴铭'
    ];
    
    $surname = mb_substr($name, 0, 2);
    if (in_array($surname, $doubleSurname)) {
        $name = mb_substr($name, 0, 2) . str_repeat('*', (mb_strlen($name, 'UTF-8') - 2));
    } else {
        $name = mb_substr($name, 0, 1) . str_repeat('*', (mb_strlen($name, 'UTF-8') - 1));
    }
    
    
    return $name;
}

// 过滤掉emoji表情
function filterEmoji(&$text_content)
{
    //对emoji进行表情过滤
    $regex = '/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u';
    $text_content = preg_replace_callback($regex,
        function ($matches) {
            return '';
        },
        $text_content);
}

/**
 * 隐藏部分文字
 *
 * @param string $string
 * @param int $sublen
 * @param number $start
 * @param string $code
 * @return string
 */
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if($code == 'UTF-8')
    {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        
        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else
    {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';
        
        for($i=0; $i< $strlen; $i++)
        {
            if($i>=$start && $i< ($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        //if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
        return $tmpstr;
    }
}