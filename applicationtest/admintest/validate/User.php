<?php

namespace app\admintest\validate;

use think\Validate;

class User extends Validate
{
	protected $rule = [
		['username'/*对应数据库字段名*/, 'require'/*验证要求(require=不能为空)*/, '不能为空user'/*错误提示*/],
		['password', 'require', '不能为空pass'],
	];
}
?>