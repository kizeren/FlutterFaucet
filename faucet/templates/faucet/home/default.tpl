<div class="payout_container">
<form action="{$smarty.server.SCRIPT_NAME}" method="post" class="payout_form">
  <article class="module width_full">
    <header><h3>Request {$GLOBAL.config.payout|number_format:"2"|default:"n/a"} {$GLOBAL.config.currency}</h3></header>
    <div class="module_content">
      <fieldset>
        <label>Your Receipt Address</label>
        <input type="text" name="userAddress" maxlength="64"/>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
      <input type="submit" value="Request {$GLOBAL.config.currency}" class="alt_btn">
      {/nocache}
      </div>
    </footer>
  </article>
</form>

<article class="module width_full">
   <header><h3>Please donate!</h3></header>
   <div class="module_content">
     Faucet Address: FAe5JVpn7Cyi2wGq9V9tDtHAdzREWLpvuv<br>
     Faucet PubKey: ce6TNacMkJHF2aKuYPUNc4u39WRysBymgd1Z2r3j8Fuq<br>
     Send a message!  We will respond as soon as we can!

   </div>
</article>
<center>
<article class="module width_quarter">
  <header><h3>Balance Summary</h3></header>
  <table width="25%" class="tablesorter" cellspacing="0">
  <tr>
    <td align="left">Wallet Balance</td>
    <td align="left">{$BALANCE|number_format:"8"}</td>
  </tr>
</table>
</article>

<article class="module width_3_quarter">
  <header><h3>Wallet Information</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <th align="center">Version</th>
      <th align="center">Protocol Version</th>
      <th align="center">Wallet Version</th>
      <th align="center">Connections</th>
      <th align="center">Errors</th>
    </thead>
    <tbody>
      <tr>
        <td align="center">{$COININFO.version|default:""}</td>
        <td align="center">{$COININFO.protocolversion|default:""}</td>
        <td align="center">{$COININFO.walletversion|default:""}</td>
        <td align="center">{$COININFO.connections|default:""}</td>
        <td align="center"><font color="{if $COININFO.errors}red{else}green{/if}">{$COININFO.errors|default:"OK"}</font></td>
      </tr>
    </tbody>
  </table>
</article>


<article class="module width_quarter">
  <header><h4>Addresses seen by faucet</h3></header>
  <table class="tablesorter" cellspacing="0">
      <td>Amount:</th>
      
        <td align="center">{$TOTALSEEN|number_format:"0"}</td>
  </table>
</article>
</center>

{section name=news loop=$NEWS}
    <article class="module width_half" style="text-align:left;">
      <header style><h3>{$NEWS[news].header}, <font size=\"1px\">posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font></h3></header>
      <div class="module_content">
        {$NEWS[news].content nofilter}
        <div class="clear"></div>
      </div>
    </article>
{/section}
</div>
