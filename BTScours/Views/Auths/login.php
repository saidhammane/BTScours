<div style="position:absolute;top:0px;width:100%;height:80%;">
    <nav class="navbar navbar-dark bg-dark">
        <a href='<?= ROOT.'/auths' ?>' class="btn btn-dark"><i class="fas fa-home"></i></a>
        <div>
            <a href="<?= ROOT."auths/login" ?>" class="btn btn-dark"><i class="fas fa-sign-in-alt" title="login"></i></a>
            <a href="<?= ROOT."auths/register" ?>" class="btn btn-dark float-right"><i class="fas fa-user-plus" title="signup"></i></a>
        </div>
    </nav>
    <table style="margin:auto;" class="h-100 col-md-3">
        <tr>
            <td style="vertical-align:middle">
                <div class="p-2">
                    <div style="max-width: 200px;max-height:200px;" class="m-auto">
                        <img src='<?=WEBROOT."img/users.png" ?>' alt="users" class="w-100 h-100">
                    </div>
                    <div style="width:100%;margin:auto;"><?= Notify::getHTML(); ?></div>
                    <form method="POST" style="margin:auto;">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" required name="email" value='<?= isset($email)?$email:"" ?>' class="form-control" id="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" required name="password" class="form-control" id="password" placeholder="Password" autocomplete>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        Or <a href="<?= ROOT.'auths/register' ?>">create an account</a>
                    </form>
                </div>
            </td>
        </tr>
</table>
</div>