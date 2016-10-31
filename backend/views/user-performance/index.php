<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form class="row" method="get" action="">
                        <div class="col-sm-2">
                            <input type="text" name="YejiSearch[username]" placeholder="用户名"
                                   value="<?php echo $sear['username']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="YejiSearch[realname]" placeholder="真实姓名"
                                   value="<?php echo $sear['realname']; ?>" class="input form-control">
                        </div>
                        <div class="col-sm-2">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                            </span>
                        </div>
                    </form>

                    <div class="clients-list">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;">
                                    <div class="full-height-scroll" style="width: auto; height: 100%;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="client-avatar">id</th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">用户名</a></th>
                                                    <th><a data-toggle="tab" href="#contact-3"
                                                           class="client-link">真实姓名</a></th>
                                                    <th>增值服务捆绑率</th>
                                                    <th>随心包捆绑率</th>
                                                    <th class="client-status">总提单</th>
                                                    <th>成功提单</th>
                                                    <th>总借出金额</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($data['data'] as $_k => $_v) { ?>
                                                    <tr>
                                                        <td class="client-avatar"><?= $_v['id'] ?></td>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['username'] ?></a></td>
                                                        <td><a data-toggle="tab" href="#contact-3"
                                                               class="client-link"><?= $_v['realname'] ?></a></td>
                                                        <td><?= $_v['a_services'] ?></td>
                                                        <td><?= $_v['f_packcount'] ?></td>
                                                        <td class="client-status"><?= $_v['t_ordercount'] ?></td>
                                                        <td class="client-status"><?= $_v['s_ordercount'] ?></td>
                                                        <td class="client-status"><?= $_v['s_amount'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--分页-->
                                        <div class="f-r">
                                            <?= \yii\widgets\LinkPager::widget([
                                                'pagination' => $pages,
                                                'firstPageLabel' => '首页',
                                                'nextPageLabel' => '下一页',
                                                'prevPageLabel' => '上一页',
                                                'lastPageLabel' => '末页',
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>