<div id="sheditor" title="Scheme Editor">

    <fieldset>
        <legend class="fieldset-toggle in-sheditor start-collapsed">
            <span class="arrow-icon"></span>Podešavanja
        </legend>
        <div>
            <div>
                <h4>Boja pokrivke</h4>
                <input id="sh-overlay-color" class="jui-colorpicker">
            </div>
            <div>
                <h4>Providnost pokrivke</h4>
                <div id="sh-overlay-opacity"></div>
            </div>
            <div>
                <h4>Boja konture</h4>
                <input id="sh-outline-color" class="jui-colorpicker">
            </div>
            <div>
                <h4>Debljina konture</h4>
                <input id="sh-outline-width" class="jui-spinner">
            </div>
        </div>
    </fieldset>

    <div id="target-select">
        <div><select class="jui-selectmenu in-sheditor" id="target-selectmenu"></select></div>
        <div>
            <ol class="jui-selectable disable-multi">
                <li>plain</li>
                <li>a</li>
                <li>a:hover</li>
                <li>a:active</li>
                <li>a:visited</li>
            </ol>
        </div>
    </div>

    <div class="jui-accordion in-sheditor">

        <h3>Font</h3>

        <div>
            <div data-property="font-size">
                <h4>Veličina</h4>
                <div class="spinner-unit in-sheditor"></div>
            </div>
            <div data-property="font-weight">
                <h4>Težina</h4>
                <select class="jui-selectmenu in-sheditor">
                    <option value="lighter">lighter</option>
                    <option value="bolder">bolder</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                    <option value="normal">normal</option>
                    <option value="500">500</option>
                    <option value="600">600</option>
                    <option value="bold">bold</option>
                    <option value="800">800</option>
                    <option value="900">900</option>
                </select>
            </div>
            <div data-property="font-family">
                <h4>Family</h4>
                <select class="jui-selectmenu in-sheditor"></select>
            </div>
        </div>
        <!-- Font -->

        <h3>Tekst</h3>

        <div>
            <div data-property="color">
                <h4>Boja</h4>
                <input class="jui-colorpicker">
            </div>
            <div data-property="text-align">
                <h4>Poravnanje</h4>
                <select class="jui-selectmenu in-sheditor">
                    <option value="left">left</option>
                    <option value="right">right</option>
                    <option value="center">center</option>
                    <option value="justify">justify</option>
                    <option value="justify-all">justify-all</option>
                    <option value="start">start</option>
                    <option value="end">end</option>
                    <option value="match-parent">match-parent</option>
                </select>
            </div>
            <div data-property="text-transfrom">
                <h4>Transformacije</h4>
                <select class="jui-selectmenu in-sheditor">
                   <option value="none">none</option>
                   <option value="capitalize">capitalize</option>
                   <option value="lowercase">lowercase</option>
                   <option value="full-width">full-width</option>
                </select>
            </div>
            <div data-property="text-decoration-line">
                <h4>Dekorativne linije</h4>
                <ol class="jui-selectable">
                    <li class="jui-none">none</li>
                    <li>underline</li>
                    <li>overline</li>
                    <li>line-throgh</li>
                    <li>blink</li>
                </ol>
            </div>
            <div data-property="text-decoration-style">
                <h4>Stil dekorativnih linija</h4>
                <select class="jui-selectmenu in-sheditor">
                    <option value="solid">solid</option>
                    <option value="double">double</option>
                    <option value="dashed">dashed</option>
                    <option value="wavy">wavy</option>
                </select>
            </div>
            <div data-property="text-decoration-color">
                <h4>Boja dekorativnih linija</h4>
                <input class="jui-colorpicker">
            </div>
        </div>
        <!-- Text -->

        <h3>Ivice</h3>

        <div>

            <div data-property="border-collapse">
                <h4>Collapse</h4>
                <select class="jui-selectmenu in-sheditor">
                    <option value="collapse">collapse</option>
                    <option value="separate">separate</option>
                </select>
            </div>
            <div data-property="border-width">
                <h4>Debljina</h4>
                <div class="spinner-unit in-sheditor"></div>
            </div>
            <div data-property="border-style">
                <h4>Stil</h4>
                <select class="jui-selectmenu in-sheditor"></select>
            </div>
            <div data-property="border-color">
                <h4>Boja</h4>
                <input class="jui-colorpicker">
            </div>

            <p>
                Expand fieldsets <strong>ONLY</strong> if you want to specifiy different
                border configurations for different sides. Otherwise keep it collapsed.
            </p>

            <fieldset>
                <legend class="fieldset-toggle in-sheditor start-collapsed">
                    <span class="arrow-icon"></span>Debljina
                </legend>
                <div id="border-width">
                    <div data-property="border-top-width">
                        <h4>Gornja</h4>
                        <div class="spinner-unit in-sheditor"></div>
                    </div>
                    <div data-property="border-right-width">
                        <h4>Desna</h4>
                        <div class="spinner-unit in-sheditor"></div>
                    </div>
                    <div data-property="border-bottom-width">
                        <h4>Donja</h4>
                        <div class="spinner-unit in-sheditor"></div>
                    </div>
                    <div data-property="border-left-width">
                        <h4>Leva</h4>
                        <div class="spinner-unit in-sheditor"></div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend class="fieldset-toggle in-sheditor start-collapsed">
                    <span class="arrow-icon"></span>Stil
                </legend>
                <div id="border-style">
                    <div data-property="border-top-style">
                        <h4>Top</h4>
                        <select class="jui-selectmenu in-sheditor"></select>
                    </div>
                    <div data-property="border-right-style">
                        <h4>Right</h4>
                        <select class="jui-selectmenu in-sheditor"></select>
                    </div>
                    <div data-property="border-bottom-style">
                        <h4>Bottom</h4>
                        <select class="jui-selectmenu in-sheditor"></select>
                    </div>
                    <div data-property="border-left-style">
                        <h4>Left</h4>
                        <select class="jui-selectmenu in-sheditor"></select>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend class="fieldset-toggle in-sheditor start-collapsed">
                    <span class="arrow-icon"></span>Color
                </legend>
                <div id="border-color">
                    <div data-property="border-top-color">
                        <h4>Top</h4>
                        <input class="jui-colorpicker">
                    </div>
                    <div data-property="border-right-color">
                        <h4>Right</h4>
                        <input class="jui-colorpicker">
                    </div>
                    <div data-property="border-bottom-color">
                        <h4>Bottom</h4>
                        <input class="jui-colorpicker">
                    </div>
                    <div data-property="border-left-color">
                        <h4>Left</h4>
                        <input class="jui-colorpicker">
                    </div>
                </div>
            </fieldset>

        </div>
        <!-- Border -->

        <h3>Background</h3>

        <div>
            <div data-property="background-attachment">
                <h4>Attachment</h4>
                <select class="jui-selectmenu in-sheditor">
                    <option value="scroll">scroll</option>
                    <option value="fixed">fixed</option>
                    <option value="local">local</option>
                </select>
            </div>
            <div data-property="background-style">
                <h4>Style</h4>
                <select class="jui-selectmenu in-sheditor">
                    <option value="none">none</option>
                    <option value="solid">solid</option>
                    <option value="linear-gradient">linear-gradient</option>
                    <option value="radial-gradient">radial-gradient</option>
                </select>
            </div>
            <div data-property="background-start-color">
                <h4>Start Color</h4>
                <input class="jui-colorpicker">
            </div>
            <div data-property="background-end-color">
                <h4>End Color</h4>
                <input class="jui-colorpicker">
            </div>
        </div>
        <!-- Background -->

    </div>
    <!-- .jui-accordion -->

</div> <!-- #sheditor-dialog -->
