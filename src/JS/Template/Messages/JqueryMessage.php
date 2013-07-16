<?php

namespace JS\Template\Messages;

/**
 * Description of JqueryMessage
 *
 * @author Luiz Carlos
 */
class JqueryMessage extends AbstractMessage {

    public static function error($msg) {
        return sprintf(
                "<div data-type='error'>" .
                "<div data-type='error' class='ui-state-error ui-corner-all ui-sytle-msg' style='float:right'>" .
                "<a href='javascript:;' onclick='$(this).parent().parent().empty()'>" .
                "<span class='ui-icon ui-icon-closethick'>close</span>" .
                "</a>" .
                "</div>" .
                "<div class='ui-state-error ui-corner-all ui-sytle-msg'>" .
                "<span class='ui-icon ui-icon-notice ui-style-icon'></span>%s</div></div>"
                , $msg);
    }

    public static function info($msg) {
        return sprintf(
                "<div data-type='information'>" .
                "<div data-type='information' class='ui-state-active ui-corner-all ui-sytle-msg' style='float:right'>" .
                "<a href='javascript:;' onclick='$(this).parent().parent().empty()'>" .
                "<span class='ui-icon ui-icon-closethick'>close</span>" .
                "</a>" .
                "</div>" .
                "<div class='ui-state-active ui-corner-all ui-sytle-msg'>" .
                "<span class='ui-icon ui-icon-info ui-style-icon'></span>%s</div></div>"
                , $msg);
    }

    public static function notice($msg) {
        return sprintf(
                "<div data-type='notice'>" .
                "<div class='ui-state-highlight ui-corner-all ui-sytle-msg' style='float:right'>" .
                "<a href='javascript:;' onclick='$(this).parent().parent().empty()'>" .
                "<span class='ui-icon ui-icon-closethick'>close</span>" .
                "</a>" .
                "</div>" .
                "<div class='ui-state-highlight ui-corner-all ui-sytle-msg'>" .
                "<span class='ui-icon ui-icon-alert ui-style-icon'></span>%s</div></div>"
                , $msg);
    }

}
