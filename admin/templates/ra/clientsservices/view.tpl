

<div class="row">
    <div class="col-xs-12">

        <div class="box-header with-border">
            {include file="$template/clientsservices/serviceview.tpl"}
        </div>
        <div class="box">
            <div class="box-body">
                <form method="post" action="?userid={$userid}&amp;id={$id}{if $aid}&aid={$aid}{/if}" name="frm1" id="frm1">
                    <input type="hidden" name="__fpfrm1" value="1">
                    <div class="row" style="padding:15px">

                        <div class="col-xs-6">
                            <table class="datatable table">
                                <tr>
                                    <td width="50%"><label for="orderid">Order #</label></td>
                                    <td>{$services.orderid}- <a href="orders.php?action=view&id={$services.orderid}" class="btn btn-primary">View Order</a></td>
                                </tr>
                                <tr>
                                    <td><label for="orderid">Service</label></td>
                                    <td>
                                        <select class="form-control">
                                            {$servicedrop}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="#description">Description (Address)</label></td>
                                    <td><input id="description" name="description" type="text" class="form-control" value="{$services.description}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#status">Order Status</label></td>
                                    <td>{$status}</td>
                                </tr>
                                <tr>
                                    <td><label for="#promocode">Promotion Code</label></td>
                                    <td>
                                        <select class="form-control" id="promocode" name="promocode">

                                            <option value="">None</option>
                                            {if $promo}
                                                {foreach from=$promo key=promoid item=row}
                                                    <option value="{$promoid}">{$row}</option>
                                                {/foreach}
                                            {/if}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">Subscription ID</label></td>
                                    <td><input class="form-control"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-6">
                            <table class="datatable table">
                                <tr>
                                    <td width="50%"><label for="#regdate">Registration Date</label></td>
                                    <td><input id="regdate" name="regdate" type="text" class="form-control" value="{$services.regdate}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">First Payment Amount</label></td>
                                    <td><input id="firstpaymentamount" type="text" class="form-control" value="{$services.firstpaymentamount}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">Recurring Amount</label></td>
                                    <td><input id="amount" name="amount" type="text" class="form-control" value="{$services.amount}"></td>
                                </tr>
                                <tr>
                                    <td><label for="#nextduedate">Next Due Date</label></td>
                                    <td><input id="nextduedate" name="nextduedate" type="text" class="form-control" value="{$services.nextduedate}"></td>

                                </tr>
                                <tr>
                                    <td><label for="#amount">Billing Cycle</label></td>
                                    <td>{$billingcycle}</td>
                                </tr>
                                <tr>
                                    <td><label for="#paymentmethod">Payment Method</label></td>
                                    <td>{$paymentmethod}</td>
                                </tr>

                            </table>
                        </div>

                        <div class="clearfix"></div>
                        {if $servicefield}

                            <div class="col-xs-6">
                                <table class="table">
                                    {foreach from=$servicefieldnd key=fieldidnd item=fieldsnd}
                                        {if $fieldsnd.fieldtype eq "text"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldidnd}">{$fieldsnd.fieldname}</label></td>
                                                <td><input class="form-control" id="custome{$fieldid}" name="customefield[{$fieldidnd}]" value="{$fieldsnd.value}"></td>
                                            </tr>
                                        {elseif $fieldsnd.fieldtype eq "date"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldidnd}">{$fieldsnd.fieldname}</label></td>
                                                <td><input class="form-control datecontroller" id="custome{$fieldidnd}" name="customefield[{$fieldidnd}]" value="{$fieldsnd.value}"></td>
                                            </tr>
                                        {elseif $fieldsnd.fieldtype eq "more"}
                                            {foreach from=$fieldsnd.children item=childrenfield}
                                                <tr>
                                                    <td width="50%"><label for="#custome{$childrenfield.cfid}">{$childrenfield.fieldname}</label></td>
                                                    <td><input class="form-control" id="custome{$childrenfield.cfid}" name="customefield[{$childrenfield.cfid}]" value="{$childrenfield.value}"></td>
                                                </tr>
                                            {/foreach}
                                        {else}
                                        {/if}
                                    {/foreach}
                                </table>  
                            </div>
                            <div class="col-xs-6">
                                <table class="table">
                                    {foreach from=$servicefield key=fieldid item=fields}
                                        {if $fields.fieldtype eq "text"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldid}">{$fields.fieldname}</label></td>
                                                <td><input class="form-control" id="custome{$fieldid}" name="customefield[{$fieldid}]" value="{$fields.value}"></td>
                                            </tr>
                                        {elseif $fields.fieldtype eq "date"}
                                            <tr>
                                                <td width="50%"><label for="#custome{$fieldid}">{$fields.fieldname}</label></td>
                                                <td><input class="form-control datecontroller" id="custome{$fieldid}" name="customefield[{$fieldid}]" value="{$fields.value}"></td>
                                            </tr>
                                        {elseif $fields.fieldtype eq "more"}
                                            {foreach from=$fields.children item=childrenfield}
                                                <tr>
                                                    <td width="50%"><label for="#custome{$childrenfield.cfid}">{$childrenfield.fieldname}</label></td>
                                                    <td><input class="form-control" id="custome{$childrenfield.cfid}" name="customefield[{$childrenfield.cfid}]" value="{$childrenfield.value}"></td>
                                                </tr>
                                            {/foreach}
                                        {else}
                                        {/if}
                                    {/foreach}
                                </table>  
                            </div>
                        {/if}
                        <div class="clearfix"></div>
                        <div class="box">
                            <div class="panel-heading">
                                Addon Services/Product
                                <div class="pull-right">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-fw fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                {if isset($services.addon)}
                                    <table class="datatable table" style="width:100%">
                                        <tr>
                                            <th>Reg Date</th>
                                            <th>Name</th>
                                            <th>First Payment</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Billing Cycle</th>
                                            <th>Action</th>
                                        </tr>
                                        {foreach from=$services.addon item=addons}
                                            <tr>
                                                <td>{$addons.regdate}</td>
                                                <td>{$addons.name}</td>
                                                <td>{$addons.firstpaymentamount}</td>
                                                <td>{$addons.amount}</td>
                                                <td>{$addons.servicestatus}</td>
                                                <td>{$addons.billingcycle}</td>
                                                <td>
                                                    <a href="#" class="btn btn-primary">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" onclick="doDeleteAddon({$addons.id})" class="btn btn-danger">
                                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </table>
                                {else}
                                    <div class="alert alert-danger" role="alert">
                                        This addon doen't have any addon

                                    </div>
                                {/if}
                            </div>
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Addons</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="#addonname">Addon Name</label>
                                                <select class='form-control' id='addonname' name='addonid'>
                                                    <option value="0">Please Choose Addons</option>
                                                    {foreach from=$addons item=addon}
                                                        <option value="{$addon.id}">{$addon.name}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="payment">Payment Method</label>
                                                {$paymentmethod}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary addonaddbutton">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div align="center"><input type="submit" value="Save Changes" class="btn btn-primary"> <input type="reset" value="Cancel Changes" class="btn"><br>
                            <a href="#" onclick="showDialog('delete');" style="color:#cc0000"><strong>Delete</strong></a></div>
                </form>
                <br>
                <div class="contentbox">
                    <table align="center"><tbody><tr><td>
                                    <strong>Send Message</strong>
                                </td><td>
                                    <form method="post" action="clientsemails.php?userid={$userid}" name="frm3" id="frm3">
                                        <input type="hidden" name="__fpfrm3" value="1">
                                        <input type="hidden" name="action" value="send">
                                        <input type="hidden" name="type" value="product">
                                        <input type="hidden" name="id" value="{$id}">
                                        {if $emaildropdown}
                                            <select name="messagename">
                                                {foreach item=row from=$emaildropdown}
                                                    <option value="{$row}">{$row}</option>
                                                {/foreach}
                                            </select>
                                        {/if}
                                        <input type="submit" value="Send Message" class="btn">
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="clientsemails.php?userid=2" name="frm4" id="frm4">
                                        <input type="hidden" name="__fpfrm4" value="1">
                                        <input type="hidden" name="action" value="send">
                                        <input type="hidden" name="type" value="product">
                                        <input type="hidden" name="id" value="{$id}">
                                        <input type="hidden" name="messagename" value="defaultnewacc">
                                        <input type="submit" value="Resend Product Welcome Email" class="btn">
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {foreach from=$test item=row}
                    {$row}
                {/foreach}

            </div>

        </div>
    </div>
</div>
</div>
<div class="clear"></div>
{literal}
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2").select2();
            $('.datecontroller').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '+1d'
            });
            $(".addonaddbutton").click(function () {
                $("#frm1").submit();
            });
        });
        function doDeleteAddon(id) {
            if (confirm("Are you sure you want to delete this addon?")) {
                window.location = '/admin/clientsservices.php?userid={/literal}{$userid}{literal}&action=deladdon&aid=' + id + '&token={/literal}{$token}{literal}';
            }
        }
        function runModuleCommand(cmd, custom) {
            $("#mod" + cmd).dialog("close");
            $("#modcmdbtns").css("filter", "alpha(opacity=20)");
            $("#modcmdbtns").css("-moz-opacity", "0.2");
            $("#modcmdbtns").css("-khtml-opacity", "0.2");
            $("#modcmdbtns").css("opacity", "0.2");
            var position = $("#modcmdbtns").position();
            $("#modcmdworking").css("position", "absolute");
            $("#modcmdworking").css("top", position.top);
            $("#modcmdworking").css("left", position.left);
            $("#modcmdworking").css("padding", "9px 50px 0");
            $("#modcmdworking").fadeIn();
            var reqstr = "userid=1&id=35&modop=" + cmd + "&token=0951db7664024f53758d62b7cb94336b96566473";
            if (custom)
                reqstr += "&ac=" + custom;
            else if (cmd == "suspend")
                reqstr += "&suspreason=" + encodeURIComponent($("#suspreason").val()) + "&suspemail=" + $("#suspemail").is(":checked");
            $.post("clientsservices.php", reqstr,
                    function (data) {
                        if (data.substr(0, 9) == "redirect|") {
                            window.location = data.substr(9);
                        } else {
                            $("#servicecontent").html(data);
                        }
                    });
        }
    </script>
{/literal}


