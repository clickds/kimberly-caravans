#!/bin/bash
ansible-playbook .ansible/deploy.yml -i .ansible/production -u ubuntu --extra-vars "branch=master deploy_path=/home/ubuntu/marquis"