<div class="wrap">
  <h2>Set domains from which signups are allowed</h2>
  <div style="float: left; width: 660px; margin: 5px;"> 
  <p>Adding domain names to this list will prevent signups from anyone who doesn't enter an email address with a domain name that <strong>exactly</strong> matches one in the list. For example if you enter <em>example.com</em> then <em>james@example.com</em> will be allowed, but <em>james@my.example.com</em> will not.</p>
  <p><em>Please put each domain on a new line</em></p>  
  
    <form method="post" action="">
      <p>
        <label for="options_domains" style="font-weight: bold">Domains</label><br />
        <textarea rows="20" cols="40" name="options_domains"><?php echo implode("\n", $options['domains']) ?></textarea>
      </p>
      <p><input type="submit" name="update_options" value="Update"  style="font-weight:bold;" /></p>
    </form>
  </div>
</div>

