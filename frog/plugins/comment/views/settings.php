<h1><?php echo __('Comments Plugin'); ?></h1>

<form action="<?php echo get_url('plugin/comment/save'); ?>" method="post">
    <fieldset style="padding: 0.5em;">
        <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Comments settings'); ?></legend>
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="label"><label for="autoapprove"><?php echo __('Auto approve'); ?>: </label></td>
                <td class="field">
					<select name="autoapprove">
						<option value="1" <?php if($approve == "1") echo 'selected ="";' ?>><?php echo __('Yes'); ?></option>
						<option value="0" <?php if($approve == "0") echo 'selected ="";' ?>><?php echo __('No'); ?></option>
					</select>	
				</td>
                <td class="help"><?php echo __('Choose yes if you want your comments to be auto approved. Otherwise, they will be placed in the moderation queue.'); ?></td>
            </tr>
            <tr>
                <td class="label"><label for="captcha"><?php echo __('Use captcha'); ?>: </label></td>
                <td class="field">
					<select name="captcha">
						<option value="1" <?php if($captcha == "1") echo 'selected ="";' ?>><?php echo __('Yes'); ?></option>
						<option value="2" <?php if($captcha == "2") echo 'selected ="";' ?>><?php echo __('No'); ?></option>
					</select>	
				</td>
                <td class="help"><?php echo __('Choose yes if you want to use a captcha to protect yourself against spammers.'); ?></td>
            </tr>	
            <tr>
                <td class="label"><label for="rowspage"><?php echo __('Comments per page'); ?>: </label></td>
                <td class="field">
					<input type="text" class="textinput" value="<?php echo $rowspage; ?>" name="rowspage" />
				</td>
                <td class="help"><?php echo __('Sets the number of comments to be displayed per page in the backend.'); ?></td>
        	</tr>	
        </table>
    </fieldset>
    <br/>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    </p>
</form>
