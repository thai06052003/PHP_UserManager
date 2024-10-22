<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $dataView['pageTitle'] ?? 'DXT' ?></title>
    <!-- Bootstrap v5.1.3 -->
    <link rel="stylesheet" href="<?php echo _WEB_ROOT ?>/public/assets/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo _WEB_ROOT ?>/public/assets/css/style.css?ver=<?php echo rand() ?>">
</head>

<body>
    <?php $this->render('blocks/header') ?>
    <main class="py-3">

        <div class="container">
            <div class="row">
                <div class="col-3">
                    <?php $this->render('blocks/sidebar'); ?>
                </div>
                <div class="col-9">
                    <?php $this->render($body, $dataView); ?>
                </div>
            </div>
        </div>

    </main>
    <?php $this->render('blocks/footer') ?>
    <!-- Custom JS -->
    <script type="module" src="<?php echo _WEB_ROOT; ?>/public/assets/js/script.js"></script>
    
    <!-- Api sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($msg): ?>
            Swal.fire({
                icon: "<?php echo $msgType ?>",
                title: "<?php echo $msgType === 'success' ? 'Thành công' : "Đã có lỗi xảy ra..." ?>",
                text: "<?php echo $msg ?>"
            });
        <?php endif; ?>
    </script>
</body>

</html>