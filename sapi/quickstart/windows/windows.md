# 构建window  PHP 工具 和 参考

[download windows PHP ](https://windows.php.net/download#php-8.2)

[windows build php 步骤](https://wiki.php.net/internals/windows/stepbystepbuild)

## windows 环境下 git 配置

```shell
git config --global core.autocrlf false
git config --global core.eol lf
git config --global core.ignorecase false
git config core.ignorecase false # 设置 Git 在 Windows 上也区分大小写
```

[Latest VC++](https://learn.microsoft.com/en-AU/cpp/windows/latest-supported-vc-redist)
[7zip](https://7-zip.org/)
[visualstudio](https://visualstudio.microsoft.com/zh-hans/downloads/)
[windows-sdk](https://developer.microsoft.com/en-us/windows/downloads/windows-sdk/)
[远程桌面客户端](https://learn.microsoft.com/zh-cn/windows-server/remote/remote-desktop-services/clients/remote-desktop-clients)

## windows 软连接例子

```bash

mklink composer composer.phar

```

```shell
netsh wlan show drivers
netsh wlan show profiles
#　查看密码等信息
netsh wlan show profiles name="XXXXXX" key=clear


ipconfig /all

```
