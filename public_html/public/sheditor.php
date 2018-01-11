<div id="sheditor" title="Scheme Editor">

    <fieldset>
        <legend class="fieldset-toggle start-collapsed">
            <span class="arrow-icon"></span>Podešavanja
        </legend>
        <div>
            <label>
                <h4>Boja pokrivke</h4>
                <input id="sh-overlay-color" class="jui-colorpicker">
            </label>
            <label>
                <h4>Providnost pokrivke</h4>
                <input id="sh-overlay-opacity" type="range" min="1" max="100" value="80" step="10">
            </label>
            <label>
                <h4>Boja konture</h4>
                <input id="sh-outline-color" class="jui-colorpicker">
            </label>
            <label>
                <h4>Debljina konture</h4>
                <input id="sh-outline-width" type="number" value="3" min="1" max="100">
            </label>
        </div>
    </fieldset>

    <div id="target-select">
        <div>
            <select>
                <option value="" selected></option>
                <!-- Svi elementi koji imaju data-shclass atribut. -->
            </select>
        </div>
        <div>
            <label><input type="radio" name="pseudo" value="plain"> plain</label>
            <label><input type="radio" name="pseudo" value="a"> a</label>
            <label><input type="radio" name="pseudo" value="a:hover"> a:hover</label>
            <label><input type="radio" name="pseudo" value="a:active"> a:active</label>
            <label><input type="radio" name="pseudo" value="a:visited"> a:visited</label>
        </div>
    </div>

    <div id="accordion">

        <h3>Font</h3>
        <div>
            <div data-property="font-size">
                <h4>Veličina</h4>
                <div class="number-and-unit"></div>
            </div>
            <div data-property="font-weight">
                <h4>Težina</h4>
                <select>
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
                <select></select>
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
                <select>
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
                <select>
                   <option value="none">none</option>
                   <option value="capitalize">capitalize</option>
                   <option value="lowercase">lowercase</option>
                   <option value="full-width">full-width</option>
                </select>
            </div>
            <div data-property="text-decoration-line">
                <h4>Dekorativne linije</h4>
                <label><input type="checkbox" name="text-decoration-line" value="none"> none</label>
                <label><input type="checkbox" name="text-decoration-line" value="underline"> underline</label>
                <label><input type="checkbox" name="text-decoration-line" value="overline"> overline</label>
                <label><input type="checkbox" name="text-decoration-line" value="line-throgh"> line-throgh</label>
                <label><input type="checkbox" name="text-decoration-line" value="blink"> blink</label>
            </div>
            <div data-property="text-decoration-style">
                <h4>Stil dekorativnih linija</h4>
                <select>
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
                <select>
                    <option value="collapse">collapse</option>
                    <option value="separate">separate</option>
                </select>
            </div>
            <div data-property="border-width">
                <h4>Debljina</h4>
                <div class="number-and-unit"></div>
            </div>
            <div data-property="border-style">
                <h4>Stil</h4>
                <select>
                    <!-- Imamo border-[ |top|right|bottom|left]-style sve sa istim opcijama. -->
                </select>
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
                <legend class="fieldset-toggle start-collapsed">
                    <span class="arrow-icon"></span>Debljina
                </legend>
                <div id="border-width">
                    <div data-property="border-top-width">
                        <h4>Gornja</h4>
                        <div class="number-and-unit"></div>
                    </div>
                    <div data-property="border-right-width">
                        <h4>Desna</h4>
                        <div class="number-and-unit"></div>
                    </div>
                    <div data-property="border-bottom-width">
                        <h4>Donja</h4>
                        <div class="number-and-unit"></div>
                    </div>
                    <div data-property="border-left-width">
                        <h4>Leva</h4>
                        <div class="number-and-unit"></div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend class="fieldset-toggle start-collapsed">
                    <span class="arrow-icon"></span>Stil
                </legend>
                <div id="border-style">
                    <div data-property="border-top-style">
                        <h4>Top</h4>
                        <select></select>
                    </div>
                    <div data-property="border-right-style">
                        <h4>Right</h4>
                        <select></select>
                    </div>
                    <div data-property="border-bottom-style">
                        <h4>Bottom</h4>
                        <select></select>
                    </div>
                    <div data-property="border-left-style">
                        <h4>Left</h4>
                        <select></select>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend class="fieldset-toggle start-collapsed">
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
                <select>
                    <option value="scroll">scroll</option>
                    <option value="fixed">fixed</option>
                    <option value="local">local</option>
                </select>
            </div>
            <div data-property="background-style">
                <h4>Style</h4>
                <select>
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
