<ul class="pagination">

	<?php $disPrev = ($currentPageNum === 1) ? 'disabled' : 'waves-effect'; ?>
	<li class="<?= $disPrev ?>"><a href="<?= '/' . $sortParams['sortRequest'] ?>"><i class="material-icons">chevron_left</i></a></li>

	<?php for ($pageNum = 1; $pageNum <= $pagesCount; $pageNum++): ?>
	    <?php $class = ($currentPageNum === $pageNum) ? 'active blue lighten-1' : 'waves-effect'; ?>
	    	<li class="<?= $class ?>"><a href="/<?= $pageNum === 1 ? $sortParams['sortRequest'] : 'page_' . $pageNum . $sortParams['sortRequest'] ?>"><?= $pageNum ?></a></li>
	<?php endfor; ?>

	<?php $disNext = ($currentPageNum === $pagesCount) ? 'disabled' : 'waves-effect'; ?>
	<li class="<?= $disNext ?>"><a href="<?= 'page_' . $pagesCount . $sortParams['sortRequest'] ?>"><i class="material-icons">chevron_right</i></a></li>

</ul>