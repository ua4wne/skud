<?php

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="page-header">
        <h1>Главная панель</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-5">
                    <div class="col-md-12">
                        <div class="tabbable">
                            <?= $tabs; ?>
                        </div>
                    </div><!-- /.col -->
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="widget-box transparent">
                            <div class="widget-header widget-header-flat">
                                <h4 class="widget-title lighter">
                                    <i class="ace-icon fa fa-wifi orange"></i>
                                    WiFi контроллеры
                                </h4>

                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse">
                                        <i class="ace-icon fa fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thin-border-bottom">
                                        <tr>
                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>Наименование
                                            </th>

                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>Уровень сигнала
                                            </th>

                                            <th class="hidden-480">
                                                <i class="ace-icon fa fa-caret-right blue"></i>Батарея
                                            </th>
                                            <th class="hidden-480">
                                                <i class="ace-icon fa fa-caret-right blue"></i>Статус
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?= $state; ?>
                                        </tbody>
                                    </table>
                                </div><!-- /.widget-main -->
                            </div><!-- /.widget-body -->
                        </div><!-- /.widget-box -->
                    </div><!-- /.col -->
                </div>

                <div class="col-md-7">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-bar-chart"></i>
                                Климат-контроль
                            </h4>
                        </div>
                        <div id="chart-main"></div>
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="hr hr32 hr-dotted"></div>

            <div class="row">
                <div class="col-md-5">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-cloud blue"></i>
                                Текущий прогноз погоды в <?= $content['city']; ?>
                            </h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="clearfix">
                                            <?= $content['html']; ?>
                                        </div>
                                        <div class="hr hr8 hr-double"></div>
                                        <div class="clearfix">
                                            <div id="forecast_icon"></div>
                                        </div>
                                        <div class="hr hr8 hr-double"></div>
                                    </div><!-- /.widget-main -->
                                </div><!-- /.widget-body -->
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->

                <div class="col-md-7">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-envelope-o orange2"></i>
                                <a href="/main/syslog/index">Системный журнал</a>
                            </h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-bordered table-striped">
                                    <thead class="thin-border-bottom">
                                    <tr>
                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>Тип
                                        </th>

                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>Сообщение
                                        </th>

                                        <th class="hidden-480">
                                            <i class="ace-icon fa fa-caret-right blue"></i>Дата
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?= $syslog; ?>
                                    </tbody>
                                </table>
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="hr hr32 hr-dotted"></div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
