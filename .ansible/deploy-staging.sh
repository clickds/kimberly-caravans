#!/bin/bash
ansible-playbook .ansible/deploy.yml -i .ansible/staging -u ubuntu --extra-vars "branch=staging deploy_path=/home/ubuntu/new-marquis"