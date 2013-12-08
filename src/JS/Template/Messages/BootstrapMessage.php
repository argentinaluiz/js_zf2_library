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
                "<div class='alert alert-danger alert-dismissable'>" .
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>'
                , $msg);
    }

    public static function info($msg) {
        return sprintf(
                "<div class='alert alert-success alert-dismissable'>" .
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>'
                , $msg);
    }

    public static function notice($msg) {
        return sprintf(
                "<div class='alert alert-warning alert-dismissable'>" .
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>'
                , $msg);
    }

}