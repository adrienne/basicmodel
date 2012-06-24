task :default => :docs

desc 'builds documentation'
task :docs do
  system 'rm -rf docs'
  system 'apigen --source Basicmodel/ --destination docs/ --title "Basicmodel for CodeIgniter" --todo yes --report docs/errors.xml'
end
