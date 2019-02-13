<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <p class="lead">共<?php echo $total; ?>条 <strong><?php echo $keyword; ?></strong> 的搜索结果:</p>
        <?php if (!empty($result)): ?>
        <div class="list-group">
            <?php foreach ($result as $v): ?>
            <a href="/project?pro_key=<?php echo $pro_key; ?>&doc_id=<?php echo $v['id']; ?>" class="list-group-item"><span class="text-base-blue"><?php echo $categories[$v['cid']]; ?> / <?php echo $v['title']; ?></span></span> <small><em>update by <?php echo $users[$v['update_uid']]; ?> at <?php echo date('Y-m-d H:i:s', $v['update_time']); ?></em></small></a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p><a href="/project?pro_key=<?php echo $pro_key; ?>">返回</a></p>
        <?php endif; ?>
    </div>
    <div class="col-xs-2"></div>
</div>