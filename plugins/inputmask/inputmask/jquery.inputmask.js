/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*!
* jquery.inputmask.js
* https://github.com/RobinHerbots/Inputmask
* Copyright (c) 2010 - 2019 Robin Herbots
* Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
* Version: 4.0.9
*/

(function(factory) {
    if (typeof define === "function" && define.amd) {
        define([ "jquery", "./inputmask" ], factory);
    } else if (typeof exports === "object") {
        module.exports = factory(require("jquery"), require("./inputmask"));
    } else {
        factory(jQuery, window.Inputmask);
    }
})(function($, Inputmask) {
    if ($.fn.inputmask === undefined) {
        $.fn.inputmask = function(fn, options) {
            var nptmask, input = this[0];
            if (options === undefined) options = {};
            if (typeof fn === "string") {
                switch (fn) {
                  case "unmaskedvalue":
                    return input && input.inputmask ? input.inputmask.unmaskedvalue() : $(input).val();

                  case "remove":
                    return this.each(function() {
                        if (this.inputmask) this.inputmask.remove();
                    });

                  case "getemptymask":
                    return input && input.inputmask ? input.inputmask.getemptymask() : "";

                  case "hasMaskedValue":
                    return input && input.inputmask ? input.inputmask.hasMaskedValue() : false;

                  case "isComplete":
                    return input && input.inputmask ? input.inputmask.isComplete() : true;

                  case "getmetadata":
                    return input && input.inputmask ? input.inputmask.getmetadata() : undefined;

                  case "setvalue":
                    Inputmask.setValue(input, options);
                    break;

                  case "option":
                    if (typeof options === "string") {
                        if (input && input.inputmask !== undefined) {
                            return input.inputmask.option(options);
                        }
                    } else {
                        return this.each(function() {
                            if (this.inputmask !== undefined) {
                                return this.inputmask.option(options);
                            }
                        });
                    }
                    break;

                  default:
                    options.alias = fn;
                    nptmask = new Inputmask(options);
                    return this.each(function() {
                        nptmask.mask(this);
                    });
                }
            } else if (Array.isArray(fn)) {
                options.alias = fn;
                nptmask = new Inputmask(options);
                return this.each(function() {
                    nptmask.mask(this);
                });
            } else if (typeof fn == "object") {
                nptmask = new Inputmask(fn);
                if (fn.mask === undefined && fn.alias === undefined) {
                    return this.each(function() {
                        if (this.inputmask !== undefined) {
                            return this.inputmask.option(fn);
                        } else nptmask.mask(this);
                    });
                } else {
                    return this.each(function() {
                        nptmask.mask(this);
                    });
                }
            } else if (fn === undefined) {
                return this.each(function() {
                    nptmask = new Inputmask(options);
                    nptmask.mask(this);
                });
            }
        };
    }
    return $.fn.inputmask;
});