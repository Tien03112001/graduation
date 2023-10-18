// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
  production: false,
  // api_url: 'https://bizapp.eziweb.tech/api/modules/sales',
  // definition_api_url: 'https://bizapp.eziweb.tech/api/modules/definition',
  api_url: 'http://localhost:8000/api/modules/sales',
  definition_api_url: 'http://localhost:8000/api/modules/definition',
  chatapp_url: 'https://chatapp.eziweb.tech/api/modules/chat',
  chatws_url: 'https://chatws.eziweb.tech',
  title: 'EziWeb System'
};
