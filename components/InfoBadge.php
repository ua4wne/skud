<?php
namespace app\components;

use app\modules\admin\models\Eventlog;
use app\modules\main\models\Config;
use app\modules\main\models\Syslog;
use yii\base\Widget;
//use Yii;
//use PDO;

class InfoBadge extends Widget
{
    public $type;

    public function init(){
        parent::init();
        if($this->type===null) $this->type = 'event';
    }
    public function run(){
        switch ($this->type){
            case 'event':
                return $this->GetEventCount();
                break;
            case 'count':
                return Eventlog::find()->count();
                break;
            case 'countbox':
                return Syslog::find()->where(['is_new'=>1])->count();
                break;
            case 'outbox':
                return $this->getSysLog(3);
                break;
            case 'group':
                return $this->GetEventGroup();
                break;
            case 'system':
                return $this->SysState();
                break;
            case 'uptime':
                return 'Время работы: '.$this->getUpTime();
                break;
        }
    }

    private function GetEventCount(){
        $count = Eventlog::find()->count();
        return '<span class="badge">'.$count.'</span>';
    }

    private function GetEventGroup(){
        $types = ['error','info','access'];
        $content = '';
        foreach ($types as $type){
            $count = Eventlog::find()->where(['type'=>$type])->count();
            if($type=='error'){
                $content .= '<li>
                                    <a href="/admin/events/view?type=error">
                                        <div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-danger fa fa-exclamation-triangle"></i>
														Ошибки (error)
													</span>
                                            <span class="pull-right badge badge-info">' . $count . '</span>
                                        </div>
                                    </a>
                                </li>';
            }
            if($type=='info'){
                $content .= '<li>
                                    <a href="/admin/events/view?type=info">
                                        <div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-info fa fa-info-circle"></i>
														Информация (info)
													</span>
                                            <span class="pull-right badge badge-info">' . $count . '</span>
                                        </div>
                                    </a>
                                </li>';
            }
            if($type=='access'){
                $content .= '<li>
                                    <a href="/admin/events/view?type=access">
                                        <div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-success fa fa-shield"></i>
														Доступ (access)
													</span>
                                            <span class="pull-right badge badge-info">' . $count . '</span>
                                        </div>
                                    </a>
                                </li>';
            }
        }
        return $content;
    }

    protected function SysState(){
        //memory stat
        $stat['mem_percent'] = round(shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"),0);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
        $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
        $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
        //hdd stat
        $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
        $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);

        $content = '';
        $show_load = Config::findOne(['param'=>'SHOW_SERVER_LOAD'])->val;
        if($show_load=='true')
            $load = $this->getLoad();
        if($stat['mem_percent']<50)
            $bar_state_mem='progress-bar progress-bar-info';
        else if($stat['mem_percent']>49&&$stat['hdd_percent']<75) {
            $bar_state_mem = 'progress-bar progress-bar-warning';
        }
        else {
            $bar_state_mem = 'progress-bar progress-bar-danger';
        }
        if($stat['hdd_percent']<55)
            $bar_state_hdd='progress-bar progress-bar-info';
        else if($stat['hdd_percent']>54&&$stat['hdd_percent']<85) {
            $bar_state_hdd = 'progress-bar progress-bar-warning';
        }
        else {
            $bar_state_hdd='progress-bar progress-bar-danger';
        }
        if($load<55)
            $bar_state_load='progress-bar progress-bar-info';
        else if($load>54&&$load<85) {
            $bar_state_load = 'progress-bar progress-bar-warning';
        }
        else {
            $bar_state_load='progress-bar progress-bar-danger';
        }
        $content.='<li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Занято на диске</span>
                                            <span class="pull-right">'.$stat['hdd_percent'].'%</span>
                                        </div>

                                        <div class="progress progress-mini">
                                            <div style="width:'.$stat['hdd_percent'].'%" class="'.$bar_state_hdd.'"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Занято памяти</span>
                                            <span class="pull-right">'.$stat['mem_percent'].'%</span>
                                        </div>

                                        <div class="progress progress-mini">
                                            <div style="width:'.$stat['mem_percent'].'%" class="'.$bar_state_mem.'"></div>
                                        </div>
                                    </a>
                                </li>';
        if($show_load=='true'){
            $content.=              '<li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Загрузка сервера</span>
                                            <span class="pull-right">'.$load.'%</span>
                                        </div>

                                        <div class="progress progress-mini">
                                            <div style="width:'.$load.'%" class="'.$bar_state_load.'"></div>
                                        </div>
                                    </a>
                                </li>';
        }

        return $content;
    }

    /**
     * Gets system average load
     *
     * @return string
     */
    protected function getLoad()
    {
        $name = strtolower(php_uname('s'));
        if (strpos($name, 'windows') !== FALSE) {
            return '0';
        } elseif (strpos($name, 'linux') !== FALSE) {
            return round(array_sum(sys_getloadavg()) / count(sys_getloadavg()), 2);
        }
    }

    /**
     * Gets system up-time
     *
     * @return string
     */
    protected function getUpTime()
    {
        $uptime = shell_exec('uptime -p');
        $uptime = str_replace('up','',$uptime);
        $uptime = str_replace('days','d',$uptime);
        $uptime = str_replace('hours','h',$uptime);
        $uptime = str_replace('minutes','m',$uptime);
        return $uptime;
    }

    protected function getSyslog($c){
        $posts = Syslog::find()->where(['is_new'=>1])->limit($c)->orderBy(['id'=>SORT_DESC])->all();
        $html = '';
        foreach ($posts as $post){
            $msg = $post->msg;
            $msg = strip_tags($msg);
            $msg = mb_substr($msg,0,40);
            $html .= '<li>
                        <a href="#" class="clearfix">
                            <img src="/images/avatars/avatar.png" class="msg-photo" alt="housekeeper" />
                            <span class="msg-body">
						        <span class="msg-title">
							        <span class="blue">Кузя:</span>' . $msg . ' ...
			                        </span>
                                    <span class="msg-time">
								        <i class="ace-icon fa fa-clock-o"></i>
									        <span>' . $post->created_at . '</span>
								    </span>
						    </span>
                        </a>
                     </li>';
        }

        return $html;
    }

}