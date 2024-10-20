<ul class="pagination">
    <li class="page-item <?php echo $page <= 1 ? 'disabled' : '' ?>">
        <a class="page-link" href="<?php echo self::getPaginateLink($page-1, $isQuery) ?>">Trước</a>
    </li>
    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
        <li class="page-item <?php echo ($page == $i ? 'active' : null) ?>">
            <a class="page-link" href="<?php echo self::getPaginateLink($i, $isQuery) ?>"><?php echo $i ?></a>
        </li>
    <?php endfor; ?>
    <li class="page-item <?php echo $page >= $totalPage ? 'disabled' : '' ?>">
        <a class="page-link" href="<?php echo self::getPaginateLink($page + 1, $isQuery) ?>">Sau</a>
    </li>
</ul>