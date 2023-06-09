FROM mariadb:10.6

RUN apt-get update \
    && apt-get install -y locales \
    #localeを日本語設定に変更
    && locale-gen ja_JP.UTF-8 \
		&& echo "export LANG=ja_JP.UTF-8" >> ~/.bashrc
#言語を日本語に設定
ENV LANG ja_JP.UTF-8
