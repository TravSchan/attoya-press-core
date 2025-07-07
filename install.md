

# Attoya Press Core - Install Instructions


## Core

The `attoya-press-core.git` has the main system code

- `build`  - SQL/MD for updates
- `client` - Your `attoya-press-client.git` copy
- `log`    - HTTP logs
- `public` - Main code base
- `util`   - Common utilities
  - `scaffold-create.sh`            - Creates directories that are not included in symlinks.


For each site instance you'll need to clone `attoya-press-core.git`, but do not modify the code. That will be done in your `client`.



## Client

Clone `attoya-press-client.git` and init new repo for specific client

- `config`         - Site configuration
  - `apache`                        - Apache conf to symlink to for easy updates at deployment.
  - `environment`                   - Dev/Prod based configs per deployment.
  - `sql`                           - WIP

- `public`         - Various public accessible static files.
  - `icon`                          -
  - `manifest.json`                 - Icon specifics
  - `attoya-press-style-custom.css` - Additional changes beyond the theme
  - `attoya-press-style.css`        - Attoya Press Theme

- `upload`         - Upload directory for all images and content added via admin.
- `util`           -
  - `deploy.sh`                     - Deployment automation script.
  - `symlink-create.sh`             - Creates necessary symlinks for system to function.
  - `symlink-delete.sh`             - Deletes symlinks created to prevent link issues.

- `repo-[name].md` - Used as visual clue what client repo this is. Rename this per client repo copied.




## Theme

Most instances of Attoya Press will use the default Attoya Press Theme. For this you'll need to clone `attoya-press-theme.git` in `public/wp-content/themes/`. This is mainly because we need to create CSS symlinks inside for each site instance. The `style-custom.css` is for overrides of theme and/or plugins.

Copy default.css files in the `themes/attoya-press/` directory into the client repo and make any changes needed in client copy.
- `client/public/attoya-press-style-custom.css` - Copy from `style-custom.default.css`

Default attoya press theme CSS symlinks are set here.
- `client/util/symlink-create.sh`
- `client/util/symlink-delete.sh`

If the client needs a custom theme the it should be added to the client repo under `client/theme/` and symlinked.



## Plugins

Plugins should be cloned in a directory that can be symlinked. This is to keep one copy of plugins for multiple Attoya Press uses. Plugins are also strictly revision controlled to prevent updates breaking anything.

The default source location is `/srv/site/press/plugin/`
and plugin symlinked location is `public/wp-content/plugins/`

Default symlinks are set here. Update if a different location is needed.
- `client/util/symlink-create.sh`
- `client/util/symlink-delete.sh`

If a new plugin is needed and can be used by other clients, it should be added to the default plugins list and a new git repo created to track it. If it's highly customized, then it should be added to the client repo under `client/plugin/` and symlinked.

