a:129:{s:11:"member_size";s:49:"
select height,weigh from tblmember where id='?'
";s:16:"outlet_bannerimg";s:95:"
select * from tblmainbannerimg where banner_no='?' and banner_hidden = 1 order by banner_sort
";s:25:"outlet_bannerimg_relation";s:241:"
select a.*, b.productname, b.sellprice, b.consumerprice, b.minimage
 from tblmainbannerimg_product a inner join tblproduct b on a.productcode=b.productcode
 where tblmainbannerimg_no in (select no from tblmainbannerimg where banner_no='?')
";s:14:"product_detail";s:302:"
select *,s.season_eng_name , c.color_code as color_rgb, d.brandname
from tblproduct a left outer join tblproductseason s on a.season =s.season
left outer join tblproduct_color c on a.color_code=c.color_name
left outer join tblproductbrand d on a.brand = d.bridx
where prodcode='?' AND A.display = 'Y'
";s:15:"product_detail2";s:296:"
select a.*,(select season_eng_name from tblproduct p left outer join tblproductseason s on p.season =s.season WHERE productcode = (select substr(join_productcode,1,18) as join_product from tblproduct where productcode=a.productcode)) as season_eng_name from tblproduct a where a.productcode='?'
";s:21:"product_discount_rate";s:91:"
select ins_per from tblproductbrand_point where bridx='?' and st_per <= ? and en_per >= ?
";s:21:"product_detail_option";s:173:"
select * from tblproduct_option where productcode in (select productcode from tblproduct where prodcode='?' and display='Y')
 order by productcode, option_num, option_code
";s:23:"product_erp_productcode";s:55:"
select prodcode from tblproduct where productcode='?'
";s:19:"product_multiimages";s:123:"
SELECT * FROM tblmultiimages WHERE productcode in (select productcode from tblproduct where prodcode='?' and display='Y')
";s:18:"joinproduct_detail";s:133:"
select productcode, productname, sellprice, consumerprice, production, prodcode, colorcode
from tblproduct
where productcode in (?)
";s:21:"joinproduct_sellprice";s:108:"
select sum(sellprice) sellprice, sum(consumerprice) consumerprice from tblproduct where productcode in (?)
";s:16:"joinproduct_info";s:87:"
select  productcode, productname, color_code from tblproduct where productcode in (?)
";s:25:"joinproduct_detail_option";s:105:"
select * from tblproduct_option where productcode in (?)
 order by productcode, option_num, option_code
";s:17:"product_md_choise";s:57:"
select mdchoise from tblproductbrand where brandcd= '?'
";s:25:"product_md_choise_product";s:485:"
select a.*, COALESCE(c.likecnt,0) likecnt, COALESCE(c1.likeme,0) likeme
from tblproduct a
left outer join (select count(section) likecnt, section, hott_code from tblhott_like where section='product' group by section, hott_code) c on a.prodcode=c.hott_code
left outer join (select count(section) likeme, section, hott_code from tblhott_like where section='product' and like_id='?' group by section, hott_code) c1 on a.prodcode=c1.hott_code
where a.productcode in (?)
and a.display='Y'
";s:27:"product_md_choise_product_m";s:75:"
select a.*
from tblproduct a
where a.productcode in (?)
and a.display='Y'
";s:29:"product_md_choise_product_alt";s:178:"
select a.productcode from tblproduct a inner join tblproductlink b on a.productcode=b.c_productcode
where b.c_category like '?%'
and a.display='Y'
order by a.vcnt desc
limit 4;
";s:25:"ecatalog_view_product_alt";s:98:"
select productcode from tblproduct where  brandcd='?' and display='Y' order by random() LIMIT 1;
";s:21:"product_category_best";s:849:"
select distinct(a.productcode), a.prodcode,  a.productname, a.sellprice, a.production, a.maximage, a.minimage, a.tinyimage, c.likecnt, c1.likeme
,coalesce(d.cnt, 0, d.cnt) ordcnt
from tblproduct a inner join tblproductlink b on a.productcode=b.c_productcode
left outer join (select count(section) likecnt, section, hott_code from tblhott_like where section='product' group by section, hott_code) c on a.prodcode=c.hott_code
left outer join (select count(section) likeme, section, hott_code from tblhott_like where section='product' and like_id='?' group by section, hott_code) c1 on a.prodcode=c1.hott_code
left outer join (select productcode, count(productcode) cnt from tblorderproduct group by productcode) d  on a.productcode=d.productcode
where 1=1
and b.c_category like '?%'
and a.maximage <>''
and a.display='Y'
order by ordcnt desc
limit 4
";s:23:"product_category_best_m";s:477:"
select distinct(a.productcode), a.prodcode,  a.productname, a.sellprice, a.production, a.maximage, a.minimage, a.tinyimage,coalesce(d.cnt, 0, d.cnt) ordcnt
from tblproduct a inner join tblproductlink b on a.productcode=b.c_productcode
left outer join (select productcode, count(productcode) cnt from tblorderproduct group by productcode) d  on a.productcode=d.productcode
where 1=1
and b.c_category like '?%'
and a.maximage <>''
and a.display='Y'
order by ordcnt desc
limit 4
";s:14:"product_cooper";s:150:"
select
	?
from tblbasket a inner join tblproduct b on a.productcode=b.productcode
where 1=1
and a.id='?'
and a.basketgrpidx='?'
order by a.date desc
";s:11:"basket_list";s:1519:"
select
	a.basketgrpidx, a.productcode, sum(a.quantity) as quantity , a.opt2_idx, a.delivery_type, a.delivery_price,a.date ,
	b.tinyimage, b.productname, sum(b.sellprice) as sellprice, b.consumerprice,b.buyprice, b.brand, b.prodcode,b.colorcode, COALESCE(c.likecnt,0) likecnt, COALESCE(c1.likeme,0) likeme
	, d.brandname as brandcdnm, e.name as store_name, a.reservation_date, b.reserve, e.store_code, b.staff_dc_rate ,b.cooper_dc_rate,g.group_productcode
from tblbasket a inner join tblproduct b on a.productcode=b.productcode
left outer join (select count(section) likecnt, section, hott_code from tblhott_like where section='product' group by section, hott_code) c on b.prodcode=c.hott_code
left outer join (select count(section) likeme, section, hott_code from tblhott_like where section='product' and like_id='?' group by section, hott_code) c1 on b.prodcode=c1.hott_code
left outer join tblproductbrand d on b.brand=d.bridx
left outer join tblstore e on a.store_code = e.store_code
left outer join tblmember f on a.id = f.id
left outer join tblcompanygroup g on f.company_code = g.group_code
where 1=1
?
and a.delivery_type='?'
and b.display='Y'
group by
a.basketgrpidx, a.productcode, a.quantity, a.opt2_idx, a.delivery_type,a.delivery_price,a.date ,
b.tinyimage,b.productname,b.sellprice,b.consumerprice,b.buyprice,b.brand,b.prodcode,b.colorcode, c.likecnt, c1.likeme
,d.brandname, e.name , a.reservation_date, b.reserve ,e.store_code, b.staff_dc_rate ,b.cooper_dc_rate,g.group_productcode
order by a.date desc
";s:21:"basket_product_option";s:275:"
select productcode, option_code,option_quantity from tblproduct_option where productcode in (
	select productcode from tblbasket
	where 1=1
	and ((id!='' and id='?') or (id='' and tempkey='?'))
	and delivery_type='?'
	group by productcode
) order by productcode, option_num
";s:13:"basket_delete";s:46:"
delete from tblbasket where basketidx in (?)
";s:29:"basket_basketgrpidx_basketidx";s:132:"
select basketgrpidx,basketidx
from tblbasket
where 1=1
and ((id!='' and id='?') or (id='' and tempkey='?'))
	and delivery_type='?'
";s:19:"basket_grpidx_check";s:159:"
select basketgrpidx from tblbasket
where 1=1
and productcode='?' and opt2_idx='?'
and ((id!='' and id='?') or (id='' and tempkey='?'))
	and delivery_type='?'
";s:13:"basket_grpidx";s:60:"
select max(basketgrpidx)+1 as basket_grpidx from tblbasket
";s:13:"basket_insert";s:325:"
INSERT INTO tblbasket(
tempkey, productcode, quantity, date, delivery_type,
id, optionarr, quantityarr, opt1_idx, opt2_idx,
basketgrpidx,store_code,
reservation_date, post_code, address1, address2, gps_x, gps_y, delivery_price

) VALUES (
'?','?', 1, '?', '?',
'?', '?', '?', '?', '?',
? ,'?',
'?','?','?','?','?','?','?'
)
";s:20:"basket_insert_backup";s:342:"
INSERT INTO tblbasket(
tempkey, productcode, quantity, date, delivery_type,
id, optionarr, quantityarr, opt1_idx, opt2_idx,
basketgrpidx,store_code,
reservation_date, post_code, address1, address2,
gps_x, gps_y, delivery_price, store_code

) VALUES (
'?','?', 1, '?', '?',
'?', '?', '?', '?', '?',
? ,'?',
'?','?','?','?',
'?','?','?','?'
)
";s:23:"basket_select_basketidx";s:59:"
select basketidx from tblbasket where basketgrpidx in (?)
";s:20:"basket_option_change";s:60:"
update tblbasket set opt2_idx = '?' where basketgrpidx='?'
";s:22:"basket_quantity_change";s:60:"
update tblbasket set quantity = '?' where basketgrpidx='?'
";s:13:"delete_basket";s:49:"
delete from tblbasket where basketgrpidx in (?)
";s:11:"review_list";s:458:"
select a.productcode, num, id, name, a.date, content, subject, a.ordercode, productorder_idx, upfile, upfile2, upfile3, upfile4, size , foot_width, color, quality, cm, kg, deli
,b.opt2_name
from tblproductreview a left outer join tblorderproduct b on a.productcode=b.productcode and a.ordercode=b.ordercode
where a.productcode in (select productcode from tblproduct where prodcode='?')
?
group by a.productcode, num, id, name, b.opt2_name
order by num desc
";s:15:"review_list_cnt";s:126:"
select count(*) total_cnt from tblproductreview where productcode in (select productcode from tblproduct where prodcode='?')
";s:8:"qna_list";s:389:"
select a.num as idx, a.mem_id as id, a.name, a.title as subject, to_timestamp(a.writetime) as date , a.is_secret as open_yn, a.content,
a.total_comment, to_timestamp(b.writetime) re_date , b.comment as re_content
from tblboard a left outer join tblboardcomment b on a.num = b.parent
where a.board='qna'
and pridx in (select pridx from tblproduct where prodcode='?')
?
order by a.num desc
";s:12:"qna_list_cnt";s:124:"
select count(*) total_cnt from tblboard where board='qna' and pridx in (select pridx from tblproduct where prodcode='?')
?
";s:18:"review_write_check";s:197:"
SELECT op.ordercode, op.idx FROM tblorderproduct op
JOIN tblorderinfo oi ON ( op.ordercode = oi.ordercode AND oi.id = '?' )
WHERE op.productcode = '?' AND oi.order_conf = '1'
order by op.idx desc
";s:14:"event_list_cnt";s:134:"
select count(*) total_cnt from tblpromo where 1=1 and event_type in (?)
and hidden = 1 and start_date <= now() and end_date >= now()
";s:10:"event_list";s:137:"
select * from tblpromo where 1=1 and event_type in (?)
and hidden = 1 and end_date >= now()
order by display_seq asc, idx::integer DESC
";s:18:"event_list_cnt_old";s:109:"
select count(*) total_cnt from tblpromo where 1=1 and event_type in (?)
and hidden = 1 and end_date < now()
";s:14:"event_list_old";s:136:"
select * from tblpromo where 1=1 and event_type in (?)
and hidden = 1 and end_date < now()
order by display_seq asc, idx::integer DESC
";s:12:"event_detail";s:313:"
select *, coalesce(cnt,0) cnt from
tblpromo a left outer join (select count(section) cnt, section, hott_code from tblhott_like group by section, hott_code) hc on hc.section='event' and a.idx=hc.hott_code
left outer join tblhott_like b on a.idx = b.hott_code and b.like_id='?' and b.section='event'
where idx='?'
";s:19:"event_detail_before";s:382:"
select idx,title,event_type,display_seq from tblpromo where idx = (select max(idx)
from tblpromo
where event_type in (?)
and display_seq >= ?
and display_type = 'A'
and end_date ? now()
and idx NOT IN(select idx
		from tblpromo
		where idx::integer >= '?'
		and event_type in (?)
		and display_seq = ?)
GROUP BY idx,display_seq
ORDER BY display_seq ASC, idx::integer DESC LIMIT 1)
";s:18:"event_detail_after";s:382:"
select idx,title,event_type,display_seq from tblpromo where idx = (select max(idx)
from tblpromo
where event_type in (?)
and display_seq <= ?
and display_type = 'A'
and end_date ? now()
and idx NOT IN(select idx
		from tblpromo
		where idx::integer <= '?'
		and event_type in (?)
		and display_seq = ?)
GROUP BY idx,display_seq
ORDER BY display_seq DESC, idx::integer ASC LIMIT 1)
";s:18:"event_comment_list";s:148:"
select num, to_timestamp(writetime) writetime, name, comment, c_mem_id from tblboardcomment_promo where board='?' and parent='?' order by num desc
";s:22:"event_comment_list_cnt";s:85:"
select count(*) total_cnt from tblboardcomment_promo where board='?' and parent='?'
";s:20:"event_comment_insert";s:228:"
insert into tblboardcomment_promo
(board, parent, name, ip, writetime, comment, c_mem_id )
values
('#{gubun}', #{parent}, '#{name}', '#{ip}', cast(extract(epoch from current_timestamp) as integer), '#{comment}', '#{c_mem_id}')
";s:18:"event_photo_insert";s:338:"
insert into tblboard_promo
(board,name,title,content,vfilename, vfilename2, vfilename3, vfilename4, writetime, ip,mem_id,promo_idx)
values
('photo','#{name}','#{title}','#{content}', '#{user_img1}', '#{user_img2}', '#{user_img3}','#{user_img4}', cast(extract(epoch from current_timestamp) as integer), '#{ip}','#{c_mem_id}','#{parent}')
";s:16:"event_photo_list";s:200:"
select num, to_timestamp(writetime) writetime, name, title, content, mem_id, vfilename, vfilename2, vfilename3, vfilename4 from tblboard_promo where promo_idx='?' and board='photo' order by num desc
";s:20:"event_photo_list_cnt";s:85:"
select count(*) total_cnt from tblboard_promo where promo_idx='?' and board='photo'
";s:16:"event_photo_view";s:176:"
select num, to_timestamp(writetime) writetime, name, content, title, mem_id, vfilename, vfilename2, vfilename3, vfilename4 from tblboard_promo where num='?' and board='photo'
";s:17:"event_stamp_check";s:103:"
select * from tblpoint_act where rel_job = '#{regdt}' and rel_mem_id='#{memid}' and rel_flag='@stamp'
";s:17:"event_stamp_month";s:132:"
select substring(rel_job,7,2) regdt from tblpoint_act where regdt like '#{regdt}%' and rel_mem_id='#{memid}' and rel_flag='@stamp'
";s:19:"event_product_list1";s:183:"
select '''' || replace(special_list, ',', ''',''') || '''' as pdlist from tblspecialpromo where special in ( cast((select seq from tblpromotion where promo_idx='?') as varchar(10)))
";s:19:"event_product_list2";s:135:"
select b.brandname,a.* from tblproduct a inner join tblproductbrand b on a.brand = b.bridx where productcode in (?) and a.display='Y'
";s:15:"event_tab_group";s:155:"
select a.*, b.special_list from tblpromotion a left outer join tblspecialpromo b on a.seq = b.special::integer where a.promo_idx='?' order by display_seq
";s:23:"event_tab_group_product";s:531:"
select productcode, pridx, productname, production , model,tinyimage, minimage, consumerprice, sellprice , prodcode, COALESCE(hc.cnt,0) cnt, b.hott_code, a.content, a.cooper_dc_rate, a.staff_dc_rate
from tblproduct a left outer join (select count(section) cnt, section, hott_code from tblhott_like group by section, hott_code) hc on hc.section='product' and a.prodcode=hc.hott_code
left outer join tblhott_like b on a.prodcode = b.hott_code and b.like_id='?' and b.section='product'
where productcode in (?)
and a.display='Y'
?
;
";s:27:"event_tab_group_product_opt";s:103:"
select productcode, option_code from tblproduct_option where productcode in (?) order by option_code;
";s:19:"event_roulette_view";s:57:"
select * from tblpromo where event_type='5' and idx='?'
";s:20:"mypage_entrylist_cnt";s:265:"
select count(*) total_cnt from tblpromo where idx in (
	select cast(promo_idx as character) from tblboard_promo where mem_id='?'
	union all
	select cast(parent as character) from tblboardcomment_promo where c_mem_id='?'
)
and start_date >= '?' and end_date <= '?'
";s:16:"mypage_entrylist";s:348:"
select idx, event_type, title, start_date, end_date, winner_list_content, publication_date from tblpromo where idx in (
	select cast(promo_idx as character) from tblboard_promo where mem_id='?'
	union all
	select cast(parent as character) from tblboardcomment_promo where c_mem_id='?'
)
and start_date >= '?' and end_date <= '?'
order by idx desc
";s:8:"like_cnt";s:87:"
select count(*) total_cnt from tblhott_like where section='product' and hott_code='?'
";s:10:"like_check";s:103:"
select count(*) total_cnt from tblhott_like where section='product' and like_id='?' and hott_code='?'
";s:11:"like_delete";s:78:"
delete from tblhott_like where like_id='?' and section='?' and hott_code='?'
";s:11:"like_insert";s:132:"
insert into tblhott_like (like_id, section, hott_code, regdt) values ('?','?','?',to_char(current_timestamp, 'yyyymmddHH24MISS'));
";s:19:"mypage_likelist_cnt";s:832:"
select count(*) total_cnt
from tblhott_like a inner join (select count(section) cnt, section, hott_code from tblhott_like group by section, hott_code) a1 on a.section=a1.section and a.hott_code=a1.hott_code
left outer join tblproduct b on a.hott_code = b.prodcode and a.section = 'product' and b.minimage <>'' and b.display='Y'
left outer join tbllookbook c on a.hott_code = c.no::varchar and a.section='lookbook' and c.img_file <>''
left outer join tblinstagram d on a.hott_code = d.idx::varchar and a.section='instagram'
left outer join tblmagazine e on a.hott_code = e.no::varchar and a.section='magazine'
left outer join tblecatalog f on a.hott_code = f.no::varchar and a.section='ecatalog'
left outer join tblyoutube g on a.hott_code = g.idx::varchar and a.section='movie'
and a.section not in ('event')
where a.like_id='?'
?
";s:15:"mypage_likelist";s:1173:"
select
a.hno, a.section, a.hott_code , a1.cnt,  b.model ,b.productcode
,b.productname as title_product
,c.title as title_lookbook
,d.title as title_instagram
,e.title as title_magazine

,b.minimage as imgs_product
,c.img_file as imgs_lookbook
,d.img_file as imgs_instagram
,e.img_file as imgs_magazine
,f.img_file as imgs_ecatalog
,g.youtube_id
from tblhott_like a inner join (select count(section) cnt, section, hott_code from tblhott_like group by section, hott_code) a1 on a.section=a1.section and a.hott_code=a1.hott_code
left outer join tblproduct b on a.hott_code = b.prodcode and a.section = 'product' and b.minimage <>'' and b.display='Y'
left outer join tbllookbook c on a.hott_code = c.no::varchar and a.section='lookbook' and c.img_file <>''
left outer join tblinstagram d on a.hott_code = d.idx::varchar and a.section='instagram'
left outer join tblmagazine e on a.hott_code = e.no::varchar and a.section='magazine'
left outer join tblecatalog f on a.hott_code = f.no::varchar and a.section='ecatalog'
left outer join tblyoutube g on a.hott_code = g.idx::varchar and a.section='movie'
where a.like_id='?'
and a.section not in ('event')
?
order by a.regdt desc
";s:19:"ecatalog_brand_list";s:113:"
select brandcd,brandname from tblproductbrand
where brandcd in (select brandcd from tblecatalog)
order by bridx
";s:20:"ecatalog_season_list";s:129:"
select * from tblproductseason
where season in (select season from tblecatalog where brandcd='?')
order by season_eng_name desc
";s:17:"ecatalog_list_cnt";s:432:"
select count(*) total_cnt from tblecatalog a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'ecatalog' group by section, hott_code) b on a.no::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'ecatalog' and like_id = '?' group by section, hott_code) b1 on a.no::varchar = b1.hott_code
where a.display='Y'
?
?
";s:13:"ecatalog_list";s:561:"
select a.*, COALESCE(b.cnt,0) cnt , e.brandname,  COALESCE(b1.cnt,0) mycnt from tblecatalog a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'ecatalog' group by section, hott_code) b on a.no::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'ecatalog' and like_id = '?' group by section, hott_code) b1 on a.no::varchar = b1.hott_code
LEFT OUTER JOIN tblproductbrand e on e.brandcd= a.brandcd
where a.display='Y'
?
?
order by regdate desc
";s:13:"ecatalog_view";s:521:"
SELECT l.*, li.hott_cnt my_like, b.brandname, b.brandcd,
			COALESCE((select COUNT( tl.hott_code )AS hott_cnt from tblhott_like tl WHERE tl.section = 'ecatalog' AND l.no::varchar = tl.hott_code),0) AS hott_cnt
			FROM tblecatalog l
LEFT JOIN ( SELECT hott_code, section ,COUNT( hott_code )AS hott_cnt FROM tblhott_like WHERE section = 'ecatalog' AND like_id = '?' GROUP BY hott_code, section ) li on l.no::varchar = li.hott_code
LEFT OUTER JOIN tblproductbrand b on b.brandcd= l.brandcd
WHERE l.no = ?
and l.display='Y'
";s:18:"ecatalog_view_next";s:206:"
select
(select no from tblecatalog where no = (select max(no) from tblecatalog where no < '?')) as before,
(select no from tblecatalog where no = (select min(no) from tblecatalog where no > '?')) as after
";s:25:"ecatalog_relation_product";s:111:"
select productcode, productname, minimage, tinyimage from tblproduct where productcode in (?) and display='Y'
";s:19:"lookbook_brand_list";s:113:"
select brandcd,brandname from tblproductbrand
where brandcd in (select brandcd from tbllookbook)
order by bridx
";s:20:"lookbook_season_list";s:129:"
select * from tblproductseason
where season in (select season from tbllookbook where brandcd='?')
order by season_eng_name desc
";s:19:"lookbook_brandcheck";s:53:"
select brandcd from tblproductbrand where bridx='?'
";s:17:"lookbook_list_cnt";s:432:"
select count(*) total_cnt from tbllookbook a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'lookbook' group by section, hott_code) b on a.no::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'lookbook' and like_id = '?' group by section, hott_code) b1 on a.no::varchar = b1.hott_code
where a.display='Y'
?
?
";s:13:"lookbook_list";s:556:"
select a.*, COALESCE(b.cnt,0) cnt , c.brandname,  COALESCE(b1.cnt,0) mycnt from tbllookbook a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'lookbook' group by section, hott_code) b on a.no::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'lookbook' and like_id = '?' group by section, hott_code) b1 on a.no::varchar = b1.hott_code
LEFT OUTER JOIN tblproductbrand c on c.brandcd= a.brandcd
where a.display='Y'
?
?
order by no desc
";s:13:"lookbook_view";s:521:"
SELECT l.*, li.hott_cnt my_like, c.brandname, c.brandcd,
			COALESCE((select COUNT( tl.hott_code )AS hott_cnt from tblhott_like tl WHERE tl.section = 'lookbook' AND l.no::varchar = tl.hott_code),0) AS hott_cnt
			FROM tbllookbook l
LEFT JOIN ( SELECT hott_code, section ,COUNT( hott_code )AS hott_cnt FROM tblhott_like WHERE section = 'lookbook' AND like_id = '?' GROUP BY hott_code, section ) li on l.no::varchar = li.hott_code
LEFT OUTER JOIN tblproductbrand c on c.brandcd= l.brandcd
WHERE l.no = ?
and l.display='Y'
";s:18:"lookbook_view_next";s:206:"
select
(select no from tbllookbook where no = (select max(no) from tbllookbook where no < '?')) as before,
(select no from tbllookbook where no = (select min(no) from tbllookbook where no > '?')) as after
";s:19:"lookbook_Bview_next";s:270:"
select
(select no from tbllookbook where no = (select max(no) from tbllookbook where no < '?' and brandcd='?' and display='Y')) as after,
(select no from tbllookbook where no = (select min(no) from tbllookbook where no > '?' and brandcd='?' and display='Y')) as before
";s:25:"lookbook_relation_product";s:111:"
select productcode, productname, minimage, tinyimage from tblproduct where productcode in (?) and display='Y'
";s:17:"magazine_list_cnt";s:428:"
select count(*) total_cnt from tblmagazine a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'magazine' group by section, hott_code) b on a.no::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'magazine' and like_id = '?' group by section, hott_code) b1 on a.no::varchar = b1.hott_code
where a.display='Y'
";s:13:"magazine_list";s:479:"
select a.*, COALESCE(b.cnt,0) cnt , COALESCE(b1.cnt,0) mycnt from tblmagazine a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'magazine' group by section, hott_code) b on a.no::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'magazine' and like_id = '?' group by section, hott_code) b1 on a.no::varchar = b1.hott_code
where a.display='Y'
order by ? desc
";s:13:"magazine_view";s:439:"
SELECT l.*, li.hott_cnt my_like,
			COALESCE((select COUNT( tl.hott_code )AS hott_cnt from tblhott_like tl WHERE tl.section = 'magazine' AND l.no::varchar = tl.hott_code),0) AS hott_cnt
			FROM tblmagazine l
LEFT JOIN ( SELECT hott_code, section ,COUNT( hott_code )AS hott_cnt FROM tblhott_like WHERE section = 'magazine' AND like_id = '?' GROUP BY hott_code, section ) li on l.no::varchar = li.hott_code
WHERE l.no = ?
and l.display='Y'
";s:20:"magazine_view_before";s:95:"
select no,title from tblmagazine where no = (select max(no) from tblmagazine where no < '?');
";s:19:"magazine_view_after";s:95:"
select no,title from tblmagazine where no = (select min(no) from tblmagazine where no > '?');
";s:18:"instagram_category";s:154:"
select a.hash_tags, b.bridx from tblinstagram a inner join tblproductbrand b on a.hash_tags = b.brandname
 group by hash_tags, b.bridx
 order by b.bridx
";s:18:"instagram_list_cnt";s:435:"
select count(*) total_cnt from tblinstagram a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'instagram' group by section, hott_code) b on a.idx::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'instagram' and like_id = '?' group by section, hott_code) b1 on a.idx::varchar = b1.hott_code
where a.display='Y'
?
";s:14:"instagram_list";s:575:"
select a.idx, a.title, a.link_url, a.link_m_url, a.img_file, a.img_m_file, a.regdt, a.hash_tags, COALESCE(b.cnt,0) cnt , COALESCE(b1.cnt,0) mycnt from tblinstagram a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'instagram' group by section, hott_code) b on a.idx::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'instagram' and like_id = '?' group by section, hott_code) b1 on a.idx::varchar = b1.hott_code
where a.display='Y'
?
order by regdt desc
";s:14:"movie_list_cnt";s:419:"
select count(*) total_cnt from tblyoutube a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'movie' group by section, hott_code) b on a.idx::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'movie' and like_id = '?' group by section, hott_code) b1 on a.idx::varchar = b1.hott_code
where 1=1
?
?
?
";s:10:"movie_list";s:538:"
select a.idx, a.title, a.youtube_id, a.regdate, a.display, a.brandcd, a.season, COALESCE(b.cnt,0) cnt , COALESCE(b1.cnt,0) mycnt from tblyoutube a
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'movie' group by section, hott_code) b on a.idx::varchar = b.hott_code
left outer join (select count(section) cnt, section, hott_code  from tblhott_like where section = 'movie' and like_id = '?' group by section, hott_code) b1 on a.idx::varchar = b1.hott_code
where 1=1
?
?
?
order by ? desc
";s:16:"movie_brand_list";s:112:"
select brandcd,brandname from tblproductbrand
where brandcd in (select brandcd from tblyoutube)
order by bridx
";s:17:"movie_season_list";s:128:"
select * from tblproductseason
where season in (select season from tblyoutube where brandcd='?')
order by season_eng_name desc
";s:12:"movie_insert";s:113:"
insert into tblyoutube (title, youtube_id, display, brandcd, season, regdate) values ('?','?','?','?','?','?');
";s:12:"movie_update";s:116:"
update tblyoutube set title ='?', youtube_id ='?', display='?', brandcd='?', season='?', regdate='?' where idx='?'
";s:9:"movie_del";s:38:"
delete from tblyoutube where idx='?'
";s:10:"movie_view";s:436:"
SELECT l.*, li.hott_cnt my_like,
			COALESCE((select COUNT( tl.hott_code )AS hott_cnt from tblhott_like tl WHERE tl.section = 'movie' AND l.idx::varchar = tl.hott_code),0) AS hott_cnt
			FROM tblyoutube l
LEFT JOIN ( SELECT hott_code, section ,COUNT( hott_code )AS hott_cnt FROM tblhott_like WHERE section = 'movie' AND like_id = '?' GROUP BY hott_code, section ) li on l.idx::varchar = li.hott_code
WHERE l.idx = ?
and l.display='Y'

";s:17:"movie_view_before";s:97:"
select idx,title from tblyoutube where idx = (select max(idx) from tblyoutube where idx < '?');
";s:16:"movie_view_after";s:97:"
select idx,title from tblyoutube where idx = (select min(idx) from tblyoutube where idx > '?');
";s:21:"admin_timesale_update";s:173:"
update tblproduct_timesale set
title ='?',  price_rate ='?',  rate_type ='?',  sdate ='?',  edate ='?',  order_time ='?',  week ='?',  newday ='?'
where timesale_sno = '?'
";s:18:"admin_product_list";s:207:"
select productcode,productname,sellprice,production,tinyimage
from tblproduct
where productcode in (select c_productcode from tblproductlink where c_category like '?%')
and display='Y'
order by productcode
";s:18:"admin_product_code";s:110:"
select code_a, code_b, code_c, code_d, code_name from tblproductcode order by code_a, code_b, code_c, code_d
";s:27:"admin_category_product_list";s:65:"
select c_productcode from tblproductlink where c_category = '?'
";s:20:"admin_category_move1";s:207:"
update tblproductlink set c_category = '?' where c_productcode in (?) and c_category = '?'
and c_productcode not in (select c_productcode from tblproductlink where c_category='?' and c_productcode in (?) )
";s:20:"admin_category_move2";s:384:"
insert into tblproductlink
(c_productcode, c_category, c_maincate, c_date, c_date_1, c_date_2, c_date_3, c_date_4 )
select c_productcode, '?', c_maincate, c_date, c_date_1, c_date_2, c_date_3, c_date_4
from tblproductlink where c_category='?' and c_productcode in (?)
and c_productcode not in (select c_productcode from tblproductlink where c_category='?' and c_productcode in (?) )
";s:27:"admin_category_move2_backup";s:157:"
insert into tblproductlink (c_productcode, c_category, c_maincate, c_date, c_date_1, c_date_2, c_date_3, c_date_4) values ('?','?','?','?','?','?','?','?')
";s:32:"admin_category_groupmatch_select";s:104:"
select gubun from tblproductcode_match where gubun='1' and standard_code = '?' and matching_code = '?'
";s:30:"admin_category_groupmatch_list";s:1230:"
select standard_code
,(select code_name from tblproductcode where concat(substring(standard_code,1,3),'000000000') = concat(code_a,code_b,code_c,code_d)) standard_name1
,(select code_name from tblproductcode where concat(substring(standard_code,1,6),'000000') = concat(code_a,code_b,code_c,code_d)) standard_name2
,(select code_name from tblproductcode where concat(substring(standard_code,1,9),'000') = concat(code_a,code_b,code_c,code_d)) standard_name3
,(select code_name from tblproductcode where concat(substring(standard_code,1,12),'') = concat(code_a,code_b,code_c,code_d)) standard_name4
, matching_code
,(select code_name from tblproductcode where concat(substring(matching_code,1,3),'000000000') = concat(code_a,code_b,code_c,code_d)) matching_name1
,(select code_name from tblproductcode where concat(substring(matching_code,1,6),'000000') = concat(code_a,code_b,code_c,code_d)) matching_name2
,(select code_name from tblproductcode where concat(substring(matching_code,1,9),'000') = concat(code_a,code_b,code_c,code_d)) matching_name3
,(select code_name from tblproductcode where concat(substring(matching_code,1,12),'') = concat(code_a,code_b,code_c,code_d)) matching_name4
from tblproductcode_match where gubun='1'
";s:32:"admin_category_groupmatch_delete";s:98:"
delete from tblproductcode_match where gubun='1' and standard_code = '?' and matching_code = '?'
";s:32:"admin_category_groupmatch_insert";s:95:"
insert into tblproductcode_match (gubun, standard_code, matching_code) values ('1', '?','?');
";s:21:"admin_timesale_insert";s:164:"
insert into tblproduct_timesale
(timesale_type, title, price_rate, rate_type, sdate, edate, order_time, week, newday)
values
('?','?','?','?','?','?','?','?','?')
";s:19:"admin_timesale_list";s:63:"
select *  from tblproduct_timesale order by timesale_sno desc
";s:18:"admin_timesale_del";s:58:"
delete from tblproduct_timesale where timesale_sno = '?'
";s:27:"admin_timesale_product_list";s:204:"
select productcode,productname,sellprice,production,tinyimage,prodcode  ,model, display,season_year, season, sellprice_dc_rate, regdate, soldout
from tblproduct where timesale_code = '?'
order by ? desc
";s:29:"admin_timesale_product_insert";s:68:"
update tblproduct set timesale_code = '?' where productcode in (?)
";s:29:"admin_timesale_product_delete";s:69:"
update tblproduct set timesale_code = null where productcode in (?)
";s:36:"admin_timesale_category_product_list";s:416:"
select productcode,productname,sellprice,production,tinyimage,prodcode  ,model, display,season_year, season, sellprice_dc_rate, regdate, soldout
,c.brandname
from tblproduct a
left outer join tblproductbrand c on a.brand=c.bridx
where 1=1
and (timesale_code is null or timesale_code <> ?)
and productcode in (select c_productcode from tblproductlink where c_category like '?%')
?
?
?
?
?
?
?
order by prodcode desc
";s:29:"admin_price_change_brand_list";s:431:"
select a.season, a.production as brandname,  a.staff_dc_rate, a.cooper_dc_rate, a.season_year ,b.season_kor_name, a.brandcd , b.season_year as year
from tblproduct a inner join tblproductseason b on a.season = b.season
where ? ?
and a.dc_rate_check = 'Y'
group by  a.season, a.production, a.staff_dc_rate, a.cooper_dc_rate, a.season_year ,b.season_kor_name, a.brandcd , b.season_year
order by b.season_kor_name desc ,a.production
";s:30:"admin_price_change_season_list";s:268:"
select a.season_year, a.season, b.season_kor_name, b.season_eng_name from tblproduct a inner join tblproductseason b on a.season_year=b.season_year and a.season=b.season
group by a.season_year, a.season, b.season_kor_name, b.season_eng_name
order by season_year desc
";s:31:"admin_price_change_brand_update";s:150:"
update tblproduct set sellprice_dc_rate = '?', sellprice = (consumerprice*(1-0.?)) where production='?' and season='?' and ? and dc_rate_check = 'Y'
";s:37:"admin_price_change_brand_update_staff";s:109:"
update tblproduct set staff_dc_rate = '?' where production='?' and season='?' and ? and dc_rate_check = 'Y'
";s:38:"admin_price_change_brand_update_cooper";s:110:"
update tblproduct set cooper_dc_rate = '?' where production='?' and season='?' and ? and dc_rate_check = 'Y'
";s:26:"admin_price_setting_delete";s:105:"
delete from tblproductdiscount where season='?' and brandcd='?' and item_gubun='?' and season_year='?';
";s:26:"admin_price_setting_insert";s:114:"
insert into tblproductdiscount (season, brandcd, item_gubun, discount,season_year) values ('?','?','?','?','?');
";s:16:"admin_brand_list";s:46:"
select * from tblproductbrand order by bridx
";s:17:"admin_season_list";s:62:"
select * from tblproductseason order by season_eng_name desc
";}