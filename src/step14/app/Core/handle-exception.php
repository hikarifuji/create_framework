<?php
declare(strict_types=1);

return function () {

    $handleException = function (Throwable $e) {
        (new App\Modules\Common\Controllers\ExceptionController())->showAction($e);
    };
    set_exception_handler($handleException);

};