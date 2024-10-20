<ul class="pagination">
    <li class="page-item <?php echo $page <= 1 ? 'disabled' : '' ?>">
        <a class="page-link" href="<?php echo self::getPaginateLink($page - 1, $isQuery) ?>">Trước</a>
    </li>
    <li class="page-item <?php echo ($page == 1 ? 'active' : null) ?>">
        <a class="page-link" href="<?php echo self::getPaginateLink(1, $isQuery) ?>">1</a>
    </li>
    <?php if ($begin > 2): ?>
        <li class="page-item">
            <span class="page-link">...</span>
        </li>
    <?php
    endif;
    for ($i = $begin; $i <= $end; $i++): ?>
        <li class="page-item <?php echo ($page == $i ? 'active' : null) ?>">
            <a class="page-link" href="<?php echo self::getPaginateLink($i, $isQuery) ?>"><?php echo $i ?></a>
        </li>
    <?php
    endfor;
    if ($end+1 != $totalPage):
    ?>
    <li class="page-item">
        <span class="page-link">...</span>
    </li>
    <?php
    endif;
    ?>
    <li class="page-item <?php echo ($page == $totalPage ? 'active' : null) ?>">
        <a class="page-link" href="<?php echo self::getPaginateLink($totalPage, $isQuery) ?>"><?php echo $totalPage ?></a>
    </li>
    <li class="page-item <?php echo $page >= $totalPage ? 'disabled' : '' ?>">
        <a class="page-link" href="<?php echo self::getPaginateLink($page + 1, $isQuery) ?>">Sau</a>
    </li>
</ul>