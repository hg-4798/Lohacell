<?if($_data->icon_type == 'tem_001'){?>

	 <ul class="<?if (strlen($mini_file1)>0) {echo "webzin";}else{echo "webzin30";}?>">
<!-- <li class="cell5 boardcheckbox"><input type="checkbox" name="" value="" class="" /></li>-->
	 <li class="cell10 boardlist_no"><?=$number?></li>
	<? if (strlen($mini_file1)>0) { ?> 
	 <li class="cell10"><span><?=$mini_file1?></span></li>
	 <li class="cell55 boardtitle"><?=$subject?> <?if($comment_tot){echo "<font style=\"font-size:8pt;font-family:Tahoma;font-weight:normal\">(<font color=\"red\">{$comment_tot}</font>)</font>";}?></li>
	<? }else{ ?>
	 <li class="cell65 boardtitle"><?=$subject?> <?if($comment_tot){echo "<font style=\"font-size:8pt;font-family:Tahoma;font-weight:normal\">(<font color=\"red\">{$comment_tot}</font>)</font>";}?></li>
	<? }?>
	 <li class="cell10" align ='center'><?=$user_name?></li>
	 <?=$hide_date_start?>
	 <li class="cell10 boarddate"><?=str_replace("/", "-", substr($reg_date, 0, 10))?></li>
	 <?=$hide_date_end?>
	 <li class="cell5 boardhit" ><?=$hit?></li>
	 </ul>
	 
	
<?}else{?>
		<!-- ��� �κ� ���� -->
	<tr>
		<td colspan="<?=$table_colcnt?>">
		<div class="subject">

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<col></col>
		<col width="80"></col>
		<?=$hide_hit_start?>
		<col width="70"></col>
		<?=$hide_hit_end?>
		<?=$hide_date_start?>
		<col width="123"></col>
		<?=$hide_date_end?>
		<TR align="center">
			<TD align="left">
				<div style="white-space:nowrap;width:<?=(int)$setup['board_width']-290?>px;overflow:hidden;text-overflow:ellipsis;"><b><?=$subject?></b></div>
			</TD>

			<?if($hide_date_start && $hide_hit_start) {?>
			<TD><nobr>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center"><B><font color="#00FFFF" style="font-size:8pt;"><?=$str_name?></font></TD>
				<td align="right"><IMG SRC="<?=$imgdir?>/board_skin1_img02.gif" border="0"></td>
			</tr>
			</table>
			</TD>
			<?} else if($hide_hit_start) {?>
			<TD><nobr><B><font color="#00FFFF" style="font-size:8pt;"><?=$str_name?></font></TD>
			<TD><nobr>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center"><font color="#4B4B4B" style="font-size:8pt;"><?=$reg_date?></font></TD>
				<td align="right"><IMG SRC="<?=$imgdir?>/board_skin1_img02.gif" border="0"></td>
			</tr>
			</table>
			</TD>
			<?} else if($hide_date_start) {?>
			<TD><nobr><B><font color="#00FFFF" style="font-size:8pt;"><?=$str_name?></font></TD>
			<TD><nobr>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center"><font color="#4B4B4B" style="font-size:8pt;">��ȸ : <?=$hit?></font></TD>
				<td align="right"><IMG SRC="<?=$imgdir?>/board_skin1_img02.gif" border="0"></td>
			</tr>
			</table>
			</TD>
			<?} else {?>
			<TD><nobr><B><font  style="font-size:8pt;"><?=$str_name?></font></TD>
			<TD><nobr><font color="#4B4B4B" style="font-size:8pt;">��ȸ : <?=$hit?></font></TD>
			<TD><nobr>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center"><font color="#4B4B4B" style="font-size:8pt;"><?=$reg_date?></font></TD>
			</tr>
			</table>
			</TD>
			<?}?>
		</TR>
		</table>
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="<?=$table_colcnt?>" style="padding:10px;padding-bottom:20px;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<? if (strlen($mini_file1)>0) { ?>
			<td width="110" valign="top" style="padding-right:10;"><?=$mini_file1?></td>
		<? } ?>
			<td valign="top" style="word-break:break-all;">
			<span style="line-height:120%">
			<?if ($deleted != "1") {?><a href="board.php?pagetype=view&view=1&num=<?=$row['num']?>&board=<?=$board?>&block=<?=$nowblock?>&gotopage=<?=$gotopage?>&search=<?=$search?>&s_check=<?=$s_check?>"><?}?>
			<?=len_title(strip_tags($row['content']), 300)?>
			<?if ($deleted != "1") {?></a><?}?>
			</span>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<!-- ��� �κ� �� -->
<?}?>