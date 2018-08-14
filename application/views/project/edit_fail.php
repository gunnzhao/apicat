<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h3 class="text-center">该文档当前正在被<?php echo $edit_user; ?>修改，请稍后再试。</h3>
<br>
<h4 class="text-center"><span class="second">3</span>秒后<a href="/project?pro_key=<?php echo $pro_key; ?>&doc_id=<?php echo $doc_id; ?>">返回</a></h4>
