<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace Union\Sdk\Sts;

use Union\Sdk\AliCore\CoreEntrance;
use Union\Sdk\AliCore\Profile\DefaultProfile;
use Union\Sdk\AliCore\DefaultAcsClient;

class StsEntrance
{
	public static function getAssumeRole($sessionname){

		CoreEntrance::init();

		$conf = union_config('union.sdk.sts');

		// 你需要操作的资源所在的region，STS服务目前只有杭州节点可以签发Token，签发出的Token在所有Region都可用
		// 只允许子用户使用角色
		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $conf['OSS_ACCESS_ID'], $conf['OSS_ACCESS_KEY']);
		$client = new DefaultAcsClient($iClientProfile);

		// 在扮演角色(AssumeRole)时，可以附加一个授权策略，进一步限制角色的权限；
		// 详情请参考《RAM使用指南》
		// 此授权策略表示读取所有OSS的只读权限

		$request = new AssumeRoleRequest();
		// RoleSessionName即临时身份的会话名称，用于区分不同的临时身份
		// 您可以使用您的客户的ID作为会话名称
		$request->setRoleSessionName($sessionname);
		$request->setRoleArn($conf['roleArn']);// 角色资源描述符，在RAM的控制台的资源详情页上可以获取
		$request->setPolicy($conf['policy']);
		$request->setDurationSeconds(3600);
		$response = $client->doAction($request);
		if($response->getStatus()==200){
			return json_decode($response->getBody(),true);//返回数组
		}else{
			return [];
		}
	}
}