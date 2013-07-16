<?php

namespace JS\Template\Messages;

/**
 * Description of BootstrapMessage
 *
 * @author Luiz Carlos
 */
class BootstrapMessage extends AbstractMessage {

    public static function error($msg) {
        return sprintf(
                "<div class='alert alert-error' style='margin-top:10px'>" .
                '<button type="button" class="close" data-dismiss="alert">&times;</button>%s</div>'
                , $msg);
    }

    public static function info($msg) {
        return sprintf(
                "<div class='alert alert-success' style='margin-top:10px'>" .
                '<button type="button" class="close" data-dismiss="alert">&times;</button>%s</div>'
                , $msg);
    }

    public static function notice($msg) {
        return sprintf(
                "<div class='alert alert-block' style='margin-top:10px'>" .
                '<button type="button" class="close" data-dismiss="alert">&times;</button>%s</div>'
                , $msg);
    }

}