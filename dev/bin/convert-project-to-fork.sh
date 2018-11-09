#!/usr/bin/env bash
default_domain=square1.tribe
nginx_conf_path=./dev/docker/phpdocker/nginx/nginx.conf
docker_compose_path=./dev/docker/docker-compose.yml
wp_cli_path=./dev/docker/wp-cli.yml
global_docker_path=./dev/docker/global

clear_project () {
	read -r -p "What is the project ID? " project_id
	echo $project_id > dev/docker/.projectID

	read -r -p "What is the project domain? (e.g. foobar.tribe) " project_domain

	# sed differs in arguments between Linux and MacOS.
	# Performing an in-place replacement without generating backup file is worth looking into on a rainy day.
	sed -i.bak "s|server_name square1.tribe;|server_name $project_domain;|" $nginx_conf_path
	rm $nginx_conf_path.bak

	sed -i.bak "s|VIRTUAL_HOST=$default_domain,*.$default_domain|VIRTUAL_HOST=$project_domain,*.$project_domain|" $docker_compose_path
	rm $docker_compose_path.bak

	sed -i.bak -E "s|url: $default_domain|url: $project_domain|" $wp_cli_path
	rm $wp_cli_path.bak

	if [ -d $global_docker_path ]
	then
		printf "Removing $global_docker_path\n"
		rm -r $global_docker_path
	fi

	printf "Updating Git origin URL\n"
	git remote set-url origin https://github.com/modern-tribe/$project_id.git
	git remote -v
}

printf "/!\ WARNING /!\ \nThis will permanently remove files from this repo.\n"
read -r -p "Are you sure you want to continue? [y/N] " response

case "$response" in
	[yY][eE][sS]|[yY])
		clear_project
		;;
    *)
		exit 0
		;;
esac
