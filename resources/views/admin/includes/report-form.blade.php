<p>Označite koja polja želite da budu prisutna u izveštaju, a onda označite da li i kako želite da sortirate po tom polju.</p>
<table>
    @foreach ($columns as $_column)
        <tr>
            <td style="padding-right:10px;">
                <label>
                    <input type="checkbox" name="columns[]" value="{{ $_column }}">
                    {{ $_column }}
                </label>
            </td>
            <td>
                <select name="sort[]" class="form-control" disabled>
                    <option value="" selected></option>
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                </select>
            </td>
        </tr>
    @endforeach
</table>
<button type="submit" class="btn btn-primary">Generiši</button>
