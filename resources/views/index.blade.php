    <style>
        a.status-1{
            font-weight: bold;
        }
        .table tr td{
            white-space: wrap !important;
        }
        .editable{
            text-decoration: none;
            border-bottom:1px dotted;
        }
        .empty{
            color: #ff0000;
            font-style: italic;
        }
        .empty:before{
            color: #ff0000;
            content: 'Empty';
        }
        .alert{
            margin-bottom:0;
            margin-top:0.5rem;
        }
    </style>    

    <div class="card">
        <div class="container-fluid card-header with-border">
            <div class="row">
                <div class="col-auto align-items-center">
                    @isset($group)<a href="<?= action('\OpenAdmin\TranslationManager\Controller@getIndex') ?>" class="btn float-start me-3 btn-light"><i class="icon-arrow-left me-2"></i>Back</a>@endisset
                    <h3 class="card-title">Manage translations @isset($group) for: <b>{{$group}}</b> @endisset</h3>
                    
                </div>
                <div class="col-auto me-auto">
                    <div class="input-group">
                        <select name="group" id="group" class="form-control group-select form-select" autocomplete="off">
                            <?php foreach($groups as $key => $value): ?>
                                <option value="<?php echo $key ?>"<?php echo $key == $group ? ' selected="true"' : ''; ?>><?php echo $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <?php if(isset($group)) : ?>
                        <form class="form-inline form-publish confirm" method="POST" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postPublish', $group) ?>" data-remote="true" role="form" data-confirm="Are you sure you want to publish the translations group '<?php echo $group ?>? This will overwrite existing language files.">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <button type="submit" class="btn btn-primary" data-disable-with="Publishing.." ><i class="icon-spell-check me-2"></i>Publish translations</button>                        
                        </form>
                    <?php else : ?>
                        <form class="form-inline form-publish-all confirm" method="POST" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postPublish', '*') ?>" data-remote="true" role="form" data-confirm="Are you sure you want to publish all translations group? This will overwrite existing language files.">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <button type="submit" class="btn btn-primary" data-disable-with="Publishing.." ><i class="icon-spell-check me-2"></i>Publish all groups translations</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div>Warning, translations are not visible until they are exported back to the app/lang file, using <code>php artisan translation:export</code> command or publish button.</div>
            <div class="alert alert-success success-import" style="display:none;">
                <p>Done importing, processed <strong class="counter">N</strong> items! Reload this page to refresh the groups!</p>
            </div>
            <div class="alert alert-success success-find" style="display:none;">
                <p>Done searching for translations, found <strong class="counter">N</strong> items!</p>
            </div>
            <div class="alert alert-success success-publish" style="display:none;">
                Done publishing the translations for group '<?php echo $group ?>'!
            </div>
            <div class="alert alert-success success-publish-all" style="display:none;">
                Done publishing the translations for all group!
            </div>
            <div class="alert alert-success success-auto-translate" style="display:none;">
                Done with auto translations for tthis group!
            </div>
            <?php if(Session::has('successPublish')) : ?>
                <div class="alert alert-info">
                    <?php echo Session::get('successPublish'); ?>
                </div>
            <?php endif; ?>
         
            </div>            
        </div>
    </div>
   
    @empty($group)
    <div class="container-fluid">
                    
        <div class="row flex align-items-stretch">
            <div class="col-4 d-flex align-self-stretch">
                <div class="card d-flex w-100 align-self-stretch">
                    <div class="container-fluid card-header with-border">
                        <h3 class="card-title">Import from translation files</h3>
                    </div>
                    
                    <div class="card-body">                    
                        <div class="form-group">
                            <p>
                            Import translation files inside the <code>resources/lang</code> folder.
                            </p>
                            
                            <form class="form-import" method="POST" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postImport') ?>" data-remote="true" role="form">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">                        
                                <div class="input-group">
                                    <select name="replace" class="form-control form-select">
                                        <option value="0">Append new translations</option>
                                        <option value="1">Replace existing translations</option>
                                    </select>                        
                                    <button type="submit" class="btn btn-primary btn-block"  data-disable-with="Loading.."><i class="icon-download me-2"></i>Import groups</button>
                                </div>                        
                            </form>                        
                        </div>                                    
                    </div>
                </div>
            </div>

            <div class="col-4 d-flex align-self-stretch">
                <div class="card d-flex w-100 align-self-stretch">
                    <div class="container-fluid card-header with-border">
                        <h3 class="card-title">Find new translations in files</h3>
                    </div>                            
                    <div class="card-body">                    
                        <div class="form-group">
                            <p>
                            Import translation files inside the <code><resources>
                            <view></view></code> folder.
                            </p>
                        
                            <form class="form-find confirm" method="POST" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postFind') ?>" data-remote="true" role="form" data-confirm="Are you sure you want to scan you app folder? All found translation keys will be added to the database.">
                                <div class="form-group">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <button type="submit" class="btn btn-primary" data-disable-with="Searching.." ><i class="icon-search me-2"></i>Find translations in files</button>
                                </div>
                            </form>
                            
                        </div>                    
                    </div>
                </div>
            </div>
            <div class="col-4 d-flex align-self-stretch">
                <div class="card d-flex w-100 align-self-stretch">
                    <div class="container-fluid card-header with-border">
                        <h3 class="card-title">Add Group</h3>
                    </div>
                    <div class="card-body">                              
                        <form class="form-add-group" role="form" method="POST" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postAddGroup') ?>">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <p>Enter a new group name and start edit translations in that group</p>
                            <div class="input-group">                            
                                <input type="text" class="form-control" name="new-group" />
                                <button type="submit" class="btn btn-success" name="add-group"><i class="icon-plus"></i> Add Group</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>    

    
        <div class="card">
            <div class="container-fluid card-header with-border">
                <h3 class="card-title">Manage locales</h3>
            </div>
            <div class="card-body">
            
                <div class="row">
                    <div class="col-auto">
                        <form class="form-remove-locale confirm" pjax-container="true" method="POST" role="form" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postRemoveLocale') ?>" data-confirm="Are you sure to remove this locale and all of data?">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                           
                            <div class="d-inline-flex gap-3 flex-row">
                                <?php foreach($locales as $locale): ?>                        
                                    <div class="input-group d-inline-flex">
                                        <div class="form-control text-nowrap px-3"><?php echo $locale ?></div>                                        
                                        <input type="checkbox" name="remove-locale[<?php echo $locale ?>]" id="remove-locale-<?php echo $locale ?>" class="d-none">
                                        <button data-name="remove-locale-<?php echo $locale ?>" class="btn btn-danger submit-remove-locale" data-disable-with="...">
                                            &times;
                                        </button>
                                    </div>
                                    
                                <?php endforeach; ?>
                            </div>
                        </form>
                    </div>
                    <div class="col-auto">
                        <form class="form-add-locale" pjax-container="true" method="POST" role="form" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postAddLocale') ?>">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="form-group">                                                        
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" name="new-locale" class="form-control" style="min-width:220px;"  placeholder="Add new locale example: en" />                            
                                        <button type="submit" class="btn btn-success btn-block" data-disable-with="Adding.."><i class="icon-plus"></i> Add new locale</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>          
            
            </div>
        </div>        
    </div>        
    @endempty

    @isset($group)
    <div class="container-fluid">

        <div class="row flex align-items-stretch">
            <div class="col-6 d-flex align-self-stretch">
                <div class="card d-flex w-100 align-self-stretch">
                    <div class="container-fluid card-header with-border">
                        <h3 class="card-title">Add new keys</h3>
                    </div>            
                    <div class="card-body">                    
                        <form class="form-add-keys" pjax-container="true" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postAdd', array($group)) ?>" method="POST"  role="form">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="form-group">
                                <label  class="pb-1">Add new keys to this group</label>
                                <textarea class="form-control" rows="4" name="keys" placeholder="Add 1 key per line, without the group prefix"></textarea>
                            </div>
                            <div class="form-group pt-3">
                                <button type="submit" value="Add keys" class="btn btn-success"><i class="icon-plus"></i> Add keys</button>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
            <div class="col-6 d-flex align-self-stretch">
                <div class="card d-flex w-100 align-self-stretch">
                    <div class="container-fluid card-header with-border">
                        <h3 class="card-title">Auto translate  <?php if(!config('laravel_google_translate.google_translate_api_key')): ?> <i>(still disabled)</i> <?php endif; ?></h3>
                    </div>
                
                    <div class="card-body">                              

                        <form class="form-autotranslate" method="POST" role="form" action="<?php echo action('\OpenAdmin\TranslationManager\Controller@postTranslateMissing') ?>">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <?php if(!config('laravel_google_translate.google_translate_api_key')): ?>
                                <p class="pb-1">
                                    <code>If you would like to use Google Translate API, install tanmuhittin/laravel-google-translate and enter your Google Translate API key to config file laravel_google_translate</code>
                                </p>
                            <?php endif; ?>
                            <div class="d-flex <?php if(!config('laravel_google_translate.google_translate_api_key')): ?> opacity-50 disabled pe-none <?php endif; ?>">                            
                                <div class="form-group w-40">
                                    <label for="base-locale"  class="pb-1">Base Locale for Auto Translations</label>
                                    <select name="base-locale" id="base-locale" class="form-control form-select">
                                        <?php foreach ($locales as $locale): ?>
                                            <option value="<?= $locale ?>"><?= $locale ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="px-2 pt-4 mt-2 w-20"> => </div>
                                <div class="form-group w-40">
                                    <label for="new-locale" class="pb-1">Enter target locale key</label>
                                    <input type="text" name="new-locale" class="form-control w-100" id="new-locale" placeholder="Enter target locale key" />
                                </div>
                            </div>                            
                            <div class="form-group mt-3">
                                <input type="hidden" name="with-translations" value="1">
                                <input type="hidden" name="file" value="<?= $group ?>">
                                <button type="submit" class="btn btn-primary btn-block <?php if(!config('laravel_google_translate.google_translate_api_key')): ?> disabled <?php endif; ?>"  data-disable-with="Adding.."><i class="icon-cogs me-2"></i> Auto translate missing translations</button>
                            </div>
                            
                        </form>
                    </div> 
                </div>            
            </div>
        </div>
   
        <div class="card">
            <div class="container-fluid card-header with-border">
                <h3 class="card-title">Total: {{$numTranslations}}, changed: <span id="num-changed">{{ $numChanged }}</span></h3>
            </div>
            <div class="card-body p-0">
                <div  class="d-none">
                    <div id="edit-translation">
                        <div class="flex">
                            <textarea class="form-control input-large" id="translation" rows="7"></textarea>
                            <button class="btn btn-primary ie-submit" id="save-translation"><i class="icon-check"></i></button>
                            <button class="btn btn-secondary ie-cancel" id="save-translation"><i class="icon-times"></i></button>
                        </div>
                    </div>
                </div>
            
                <table class="table" style="border-collapse: separate;border-spacing: 0;">
                    <thead style="position: sticky; top: 0; background: #fafafa !important; z-index:10; ">
                    <tr>
                        <th width="15%">Key</th>
                        <?php foreach ($locales as $locale): ?>
                            <th><?= $locale ?></th>
                        <?php endforeach; ?>
                        <?php if ($deleteEnabled): ?>
                            <th>&nbsp;</th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($translations as $key => $translation): ?>
                        <tr id="<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>">
                            <td><?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?></td>
                            <?php foreach ($locales as $locale): ?>
                                <?php $t = isset($translation[$locale]) ? $translation[$locale] : null ?>
                                <td>
                                <span class="ie-wrap">
                                    <a
                                        id="<?php echo $t ? $t->id : 0 ?>"
                                        class="ie editable status-<?php echo $t ? $t->status : '0 empty' ?> locale-<?php echo $locale ?>"
                                        data-bs-toggle="popover"
                                        data-target="#edit-translation"
                                        data-locale="<?php echo $locale ?>" 
                                        data-name="<?php echo $locale . '|' . htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>"
                                        data-pk="<?php echo $t ? $t->id : 0 ?>"
                                        data-url="<?php echo $editUrl ?>"                                        
                                        data-init="0"
                                    >
                                        <?php echo $t ? htmlentities($t->value, ENT_QUOTES, 'UTF-8', false) : '' ?>
                                    </a>
                                </span>                                    
                                </td>
                            <?php endforeach; ?>
                            <?php if ($deleteEnabled): ?>
                                <td>
                                    <a href="<?php echo action('\OpenAdmin\TranslationManager\Controller@postDelete', [$group, $key]) ?>"
                                    class="delete-key confirm"
                                    data-confirm="Are you sure you want to delete the translations for '<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>?"><span
                                                class="icon-trash"></span></a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @endisset    
    <script type="text/javascript">

        document.querySelector('.group-select').addEventListener('change', function(event){
            var group = event.target.value;
            if (group) {
                admin.ajax.navigate('<?php echo action('\OpenAdmin\TranslationManager\Controller@getView') ?>/'+group);
            } else {
                admin.ajax.navigate('<?php echo action('\OpenAdmin\TranslationManager\Controller@getView') ?>/');
            }
        });

        document.querySelectorAll('form.confirm').forEach(function(form){
            form.addEventListener('submit', function(event){
                const result = confirm(form.getAttribute('data-confirm'));                                
                if (!result){                    
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    return false;
                }                
            });
        })

        document.querySelectorAll('a.confirm').forEach(function(form){
            form.addEventListener('click', function(event){
                const result = confirm(form.getAttribute('data-confirm'));                                
                if (!result){                    
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    return false;
                }                
            });
        })

        document.querySelectorAll(".submit-remove-locale").forEach(el => el.addEventListener("click",function(event){
            const name = event.target.dataset.name;            
            document.querySelector("#"+name).checked = true;
        }))
        
        var popover;
        var popovers =[]
        var active_popover;
        var active_trigger;

        function hide_ohter_popovers(me){

            popovers.forEach(popover =>{
                if (me != popover._element){
                    popover.hide();
                }
            })
        }

        document.addEventListener('click', function (event) {
            if (active_popover){
                active_popover.hide();
            }
        });

        document.querySelectorAll(".editable").forEach(el => {
            popover = new bootstrap.Popover(el, {
                html: true,
                container: 'body',
                trigger: 'manual',
                placement: 'top',
                content : function () {
                    document.querySelector("#translation").value = event.target.innerText;
                    var content = document.querySelector("#edit-translation").cloneNode(true);
                                        
                    content.querySelector("#translation").addEventListener('keydown', function (event) {
                        if (event.ctrlKey && event.keyCode == 13) {
                            saveLocale();
                        }
                    })
                    return content;
                }
            })            
            
            el.addEventListener('show.bs.popover', function (event) {
                let popover = bootstrap.Popover.getInstance(this);
                active_trigger = this;
                active_popover = popover;
                hide_ohter_popovers(popover)

                if (typeof(popover.eventsAdded) == 'undefined'){
                    popover.tip.addEventListener("click",function(event){
                        if (event.target.classList.contains("ie-cancel") || event.target.parentElement.classList.contains("ie-cancel")){
                            popover.hide();
                        }
                        if (event.target.classList.contains("ie-submit") || event.target.parentElement.classList.contains("ie-submit")){                            
                            saveLocale();
                        }
                        event.stopPropagation();
                        return false;
                    })
                    popover.eventsAdded = true;                    
                }                
            })

            el.addEventListener('shown.bs.popover', function (event) {
                let popover = bootstrap.Popover.getInstance(this);
                popover.tip.querySelector("#translation").focus()
            })            

            el.addEventListener('click', function (event) {
                bootstrap.Popover.getInstance(this).toggle();
                event.stopPropagation();
            })
            popovers.push(popover);           
        })
        
        function saveLocale(){    
            
            var value = active_popover.tip.querySelector("#translation").value;
            if (active_trigger.innerText == value){
                active_popover.hide();
                goNext()
            }else{
                admin.ajax.post(active_trigger.dataset.url, {
                    name: active_trigger.dataset.name,
                    pk: active_trigger.dataset.pk,
                    value: value,
                }, function(result){
                    
                    if (result.data.status == "ok"){                    
                        active_trigger.innerText = value
                        active_trigger.classList.remove('status-0')
                        active_trigger.classList.remove('empty')
                        active_trigger.classList.add('status-1');
                        active_popover.hide();
                        goNext();                    
                    }
                    checkChanged();
                })
            }
        }
        function checkChanged(){
            document.querySelector("#num-changed").innerText = document.querySelectorAll(".status-1").length;
        }

        function goNext(){
            var next = active_trigger.closest('tr')?.nextElementSibling?.querySelector('.editable.locale-'+active_trigger.dataset.locale);
            if (next){
                setTimeout(function() {
                    next.click()
                }, 300);
            }
        }

        document.querySelectorAll("a.delete-key").forEach(el => {
            el.addEventListener('click',function(event,result){               
                
                const tr = this.closest("tr");                
                const id = tr.getAttribute("id");                                
                admin.ajax.post(this.href, {
                    id: id,
                }, function(result){
                    if (result.data.status == "ok"){                        
                        tr.remove();
                    }
                })
                event.preventDefault();
                event.stopImmediatePropagation();
                return false;                    
            })
        })

        document.querySelector(".form-publish")?.addEventListener('submit', function (event) {            
            event.preventDefault();
            admin.form.submit(event.target,function(result){
                if (result.data.status == "ok"){
                    show(document.querySelector('div.success-publish'))   
                }
            });            
        })

        document.querySelector(".form-publish-all")?.addEventListener('submit', function (event) {            
            event.preventDefault();
            admin.form.submit(event.target,function(result){
                show(document.querySelector('div.success-publish-all'))   
            });            
        })

        document.querySelector(".form-import")?.addEventListener('submit', function (event) {            
            event.preventDefault();
            admin.form.submit(event.target,function(result){
                const success = document.querySelector('div.success-import');
                success.querySelector('strong.counter').innerText = result.data.counter;
                show(success);
                window.setTimeout(function() {
                    admin.ajax.reload();      
                },2500)                
            });            
        })

        document.querySelector(".form-find")?.addEventListener('submit', function (event) {            
            event.preventDefault();
            admin.form.submit(event.target,function(result){
                const success = document.querySelector('div.success-find');
                success.querySelector('strong.counter').innerText = result.data.counter;
                show(success);
                window.setTimeout(function() {
                    admin.ajax.reload();      
                },2500)                
            });            
        })

        document.querySelector(".form-autotranslate")?.addEventListener('submit', function (event) {            
            event.preventDefault();
            admin.form.submit(event.target,function(result){                
                show(document.querySelector('div.success-auto-translate'));
                window.setTimeout(function() {
                    admin.ajax.reload();      
                },2500)                
            });            
        })       

        // cookies for auto translation
        document.querySelector("#base-locale")?.addEventListener('change', function (event) {            
            setCookie('base_locale', event.target.value);
        })

        if (getCookie('base_locale')){
            document.querySelector("#base-locale option[value="+getCookie('base_locale')+"]")?.setAttribute('selected','selected');
        }

    </script>
