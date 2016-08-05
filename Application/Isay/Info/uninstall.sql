DROP TABLE IF EXISTS `icn_isay`;
DROP TABLE IF EXISTS `icn_isay_comment`;
DROP TABLE IF EXISTS `icn_isay_detail`;
DROP TABLE IF EXISTS `icn_isay_like`;
DROP TABLE IF EXISTS `icn_isay_category`;




/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `icn_menu` where `title` = '爱说' ;
delete from `icn_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `icn_menu` where  `title` = '爱说' ;

delete from `icn_menu` where  `url` like 'Isay/%';

delete from `icn_auth_rule` where  `module` = 'Isay';

delete from `icn_action` where  `module` = 'Isay';

delete from `icn_action_limit` where  `module` = 'Isay';