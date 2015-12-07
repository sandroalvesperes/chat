<div class="form-user">
    <table>
        <tr>
            <td>Name: *</td>
            <td>
                <input type="text" id="name" maxlength="50" class="form-input" size="50" />
            </td>
        </tr>
        <tr>
            <td>E-mail: *</td>
            <td>
                <input type="text" id="email" maxlength="120" class="form-input" size="50" />
            </td>
        </tr>
        <tr>
            <td>Sex: *</td>
            <td>
                <input type="radio" name="sex" id="male" value="M" />
                <label for="male">Male</label> &nbsp; &nbsp;

                <input type="radio" name="sex" id="female" value="F" />
                <label for="female">Female</label>
            </td>
        </tr>
        <tr>
            <td>Password: *</td>
            <td>
                <input type="text" id="password" maxlength="20" class="form-input" size="25" />
            </td>
        </tr>
        <tr>
            <td>Status: *</td>
            <td>
                <input type="checkbox" id="user_status" value="1" checked="checked" />
            </td>
        </tr>
    </table>

    <fieldset>
        <legend> Access Permission </legend>

        <input type="checkbox" id="maintain_user" value="1" />
        <label for="maintain_user">Maintain User</label> &nbsp;

        <input type="checkbox" id="support_status" value="1" />
        <label for="support_status">Change Support Status</label>
    </fieldset>

</div>