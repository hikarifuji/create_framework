<?php
declare(strict_types=1);

use App\Libs\Core\Request;
use App\Libs\UserSession;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Formatter\LineFormatter;
use App\Libs\ApplicationConfigs;
use App\Libs\Core\Container;
use App\Libs\Mailer\SwiftMailSender;
use App\Libs\Mailer\DebugMailSender;

return function () {

    // メール送信クラスのインスタンスをDIコンテナに登録
    Container::getInstance()->set(
        'mailer',
        function ($c) {
            $mailer = new SwiftMailSender();
            return $mailer;
        }
    );

    // ログ出力クラスのインスタンスをDIコンテナに登録
    Container::getInstance()->set(
        'logger',
        function ($c) {
            // ロガー名「MyApplication」を初期化します。
            $logger = new Logger('MyApplication');
            // 日別のログを記録するためにRotatingFileHandlerを追加します。
            // ファイル名はserver-YYYY-MM-DD.logの形式となり、14日より前のファイルは自動で消去されます。
            $logger->pushHandler(
                (new RotatingFileHandler(ApplicationConfigs::getInstance()->getPaths()['log'] . '/server.log', 14, Logger::DEBUG))
                    // IntrospectionProcessorを追加することで、ログ出力が行われた行番号も記録されます。
                    ->pushProcessor(new IntrospectionProcessor())
                    // UidProcessorを追加することで、PHPプログラムを実行するたびに生成される、ユニークなID値も記録されます。
                    ->pushProcessor(new UidProcessor())
            );
            // exception.logにエラー情報を記録するためにStreamHandlerを追加します。
            $logger->pushHandler(
                (new StreamHandler(ApplicationConfigs::getInstance()->getPaths()['log'] . '/exceptions.log', Logger::ERROR))
                    // LineFormatterのコンストラクタ第3引数をtrueにすることで、このハンドラでのみ、改行コードも解釈されます。
                    ->setFormatter(new LineFormatter(null, null, true))
                    // IntrospectionProcessorを追加することで、ログ出力が行われた行番号も記録されます。
                    ->pushProcessor(new IntrospectionProcessor())
            );
            return $logger;
        }
    );

    // リクエスト情報クラスのインスタンスをDIコンテナに登録
    Container::getInstance()->set(
        'request',
        function ($c) {
            return new Request();
        }
    );

    // 公開ページ用セッション情報クラスのインスタンスをDIコンテナに登録
    Container::getInstance()->set(
        'user.session',
        function ($c) {
            return new UserSession();
        }
    );

    Container::getInstance()->set(
        'UserRepository',
        function ($c) {
            return new \App\Repositories\UserMariadbRepository();
        }
    );
};