# 🚀 seo_platform 主控平台
一个支持项目寄生虫、URL劫持、蜘蛛统计的自动化 SEO 优化平台

>

---

## 📚 目录

1. [程序架构](#1-程序架构)
2. [依赖库说明](#2-依赖库说明)
3. [更新日志](#更新日志)

---

## 🧭 快速概览

本平台由两个独立模块组成：

- `seo_platform`：后台管理系统
- `seo_control`：内容输出模块（[GitHub 地址](https://github.com/seokiller/seo_control)）

二者协同工作，需分别部署为两个站点。

---

## 📦 1. 程序架构

### 🚀 搭建步骤

1. **独立站点部署**：`seo_platform` 和 `seo_control` 为独立 WEB 应用，需分别建立站点。
2. **手动配置**：程序未集成安装引导，需手动完成数据库与内容端配置。
3. **内容端配置**：

   配置文件路径：

   ```text
   application/Common/Conf/control.php
   ```

    - 配置内容端使用的 **API 地址**
    - 设置访问 **TOKEN**

4. **数据库配置**：

   配置文件路径：

   ```text
   application/Common/Conf/db.php
   ```

    - 将根目录下的 `*.sql` 文件导入至数据库（或者在宝塔面板导入）
      ```bash
      mysql -u root -p seo_platform < install.sql
      ```
    - 设置数据库用户名与密码

### 🗄️ 数据库说明

| 数据库名称     | 用途说明                             | 是否需要导入数据     |
|----------------|--------------------------------------|------------------------|
| `seo_platform` | 后台管理模块使用，需导入 `*.sql` 文件 | ✅ 需要导入            |
| `seo_spider`   | 蜘蛛访问记录数据库，仅需创建空库      | ❌ 无需导入，仅需创建 |

---

## 🧩 2. 依赖库说明

### ✅ Google reCAPTCHA

- 配置路径：
  ```text
  application/Common/Conf/config.php
  ```

- 在此文件中设置 `AK` 和 `AS`（默认已配置）

### 🔐 Google Authenticator（二次验证）

- 默认密钥：`YE5NKRPCABHLBGVZ`
- 修改方式：
  后台 → 权限控制 → 管理员列表 → 修改账户
- 推荐插件：[Chrome 身份验证器插件](https://chromewebstore.google.com/detail/%E8%BA%AB%E4%BB%BD%E9%AA%8C%E8%AF%81%E5%99%A8/bhghoamapcdpbohphigoooaddinpkbai)

---

## 📝 更新日志

### 📦 版本 4.6（2024-12-05）

- ✅ 新增 sitemap 支持
- 💾 新增缓存空间查看与清理功能
- 🛠 其他优化项

### 📦 版本 4.5（2024-11-22）

- 🐛 修复异步堵塞问题
- 🌐 支持 URL 内链规则绑定
- 🔄 新增 TL 接口模型
- 📊 数据看板 UI 优化
- ⚙️ 支持 PHP 8.0

### 📦 版本 4.2（2023-07-01）

- 支持自定义绑定接口
- 移除旧接口，统一为自定义访问

### 📦 版本 3.0（2020-10-16）

- Google 验证码替代本地验证码
- 新增项目监控、机器人通知（钉钉/CloudChat/Telegram）
- 词库、模板、URL规则可视化推送
- 蜘蛛统计分表、查询性能优化

### 📦 版本 2.5

- 新增站群蜘蛛管理（信息查看、数据总汇）

### 📦 版本 2.0

- 新增项目型劫持（404、.NET、URL重写）
- 内容端同步支持

### 📦 版本 1.5

- 新增 URL 重写（搜狗规则）
- 增强蜘蛛日志记录

---
