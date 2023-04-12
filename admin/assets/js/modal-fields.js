/**
 * @copyright  (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

(function() {
    "use strict";

    /**
     * Process modal fields in parent.
     *
     * @param   string  fieldPrefix  The fields to be updated prefix.
     * @param   string  id           The new id for the item.
     * @param   string  title        The new title for the item.
     * @param   string  catid        Future usage.
     * @param   object  object       Future usage.
     * @param   string  url          Future usage.
     * @param   string  language     Future usage.
     * @param   string  scriptType   Future usage. 2 values: addon or joomla
     *
     * @return  boolean
     *
     * @since   3.7.0
     */
    window.processModalParentAddonMusic = function (fieldPrefix, id, title, catid, url, language, object, scriptType = "addon")
    {
        if(scriptType === "addon"){
            // alert("test");
            // var fieldTable  = document.getElementById(fieldPrefix + "_table");

            // if(fieldTable.find("[data-field-name]").length){
            //
            // }

            if(typeof window["addonMusicSelect__" + fieldPrefix] !== "undefined"){
                // var data    = new Array();
                // data["id"]  = id;
                // data["title"]  = title;
                window["addonMusicSelect__" + fieldPrefix]([{"id":id,"title":title}]);
            }

        }else{
            var fieldId = document.getElementById(fieldPrefix + '_id'), fieldTitle = document.getElementById(fieldPrefix + '_name');

            // Default values.
            id       = id || '';
            title    = title || '';
            catid    = catid || '';
            object   = object || '';
            url      = url || '';
            language = language || '';

            if (id)
            {
                fieldId.value    = id;
                fieldTitle.value = title;

                if (document.getElementById(fieldPrefix + '_select'))
                {
                    document.getElementById(fieldPrefix + '_select').classList.add('hidden');
                }
                if (document.getElementById(fieldPrefix + '_new'))
                {
                    document.getElementById(fieldPrefix + '_new').classList.add('hidden');
                }
                if (document.getElementById(fieldPrefix + '_edit'))
                {
                    document.getElementById(fieldPrefix + '_edit').classList.remove('hidden');
                }
                if (document.getElementById(fieldPrefix + '_clear'))
                {
                    document.getElementById(fieldPrefix + '_clear').classList.remove('hidden');
                }
                if (document.getElementById(fieldPrefix + '_propagate'))
                {
                    document.getElementById(fieldPrefix + '_propagate').classList.remove('hidden');
                }
            }
            else
            {
                fieldId.value    = '';
                fieldTitle.value = fieldId.getAttribute('data-text');

                if (document.getElementById(fieldPrefix + '_select'))
                {
                    document.getElementById(fieldPrefix + '_select').classList.remove('hidden');
                }
                if (document.getElementById(fieldPrefix + '_new'))
                {
                    document.getElementById(fieldPrefix + '_new').classList.remove('hidden');
                }
                if (document.getElementById(fieldPrefix + '_edit'))
                {
                    document.getElementById(fieldPrefix + '_edit').classList.add('hidden');
                }
                if (document.getElementById(fieldPrefix + '_clear'))
                {
                    document.getElementById(fieldPrefix + '_clear').classList.add('hidden');
                }
                if (document.getElementById(fieldPrefix + '_propagate'))
                {
                    document.getElementById(fieldPrefix + '_propagate').classList.add('hidden');
                }
            }

            if (fieldId.getAttribute('data-required') == '1')
            {
                document.formvalidator.validate(fieldId);
                document.formvalidator.validate(fieldTitle);
            }
        }

        return false;
    };

    /**
     * Process new/edit modal fields in child.
     *
     * @param   object  element       The modal footer button element.
     * @param   string  fieldPrefix   The fields to be updated prefix.
     * @param   string  action        Modal action (add, edit).
     * @param   string  itemType      The item type (Article, Contact, etc).
     * @param   string  task          Task to be done (apply, save, cancel).
     * @param   string  formId        Id of the form field (defaults to itemtype-form).
     * @param   string  idFieldId     Id of the id field (defaults to jform_id).
     * @param   string  titleFieldId  Id of the title field (defaults to jform_title).
     * @param   string  scriptType    addon or joomla value
     *
     * @return  boolean
     *
     * @since   3.7.0
     */
    window.processModalEditAddonMusic = function (element, fieldPrefix, action, itemType, task, formId, idFieldId, titleFieldId, scriptType = "addon")
    {
        formId       = formId || itemType.toLowerCase() + '-form';
        idFieldId    = idFieldId || 'jform_id';
        titleFieldId = titleFieldId || 'jform_title';

        // var modalId = element.parentNode.parentNode.parentNode.parentNode.id,
        var modalId = element.parentNode.parentNode.parentNode.parentNode.id,
            submittedTask = task;
        modalId = (modalId.length)?modalId:element.parentNode.parentNode.id;

        var iframe  = document.getElementById(modalId).getElementsByTagName('iframe')[0];

        // Set frame id.
        iframe.id = 'Frame_' + modalId;

        var iframeDocument = iframe.contentDocument;

        // If Close (cancel task), close the modal.
        if (task === 'cancel')
        {
            // Submit button on child iframe so we can check out.
            iframe.contentWindow.Joomla.submitbutton(itemType.toLowerCase() + '.' + task);

            if(typeof jQuery('#' + modalId).data("modal") !== "undefined"){
                jQuery('#' + modalId).modal('hide');
            }else if(typeof Joomla.Modal.getCurrent() !== "undefined"){
                Joomla.Modal.getCurrent().close();
            }
        }
        // For Save (apply task) and Save & Close (save task).
        else
        {
            // Attach onload event to the iframe.
            iframe.addEventListener('load', function()
            {
                // Reload iframe document var value.
                iframeDocument = this.contentDocument;

                // Validate the child form and update parent form.
                if (iframeDocument.getElementById(idFieldId) && iframeDocument.getElementById(idFieldId).value != '0')
                {
                    window.processModalParentAddonMusic(fieldPrefix, iframeDocument.getElementById(idFieldId).value, iframeDocument.getElementById(titleFieldId).value);
                    // if(scriptType === "addon"){
                    //     window.processModalParentAddonMusic(fieldPrefix, iframeDocument.getElementById(idFieldId).value, iframeDocument.getElementById(titleFieldId).value);
                    // }else{
                    //     window.processModalParentAddonMusic(fieldPrefix, iframeDocument.getElementById(idFieldId).value, iframeDocument.getElementById(titleFieldId).value, scriptType);
                    // }

                    // If Save & Close (save task), submit the edit close action (so we don't have checked out items).
                    if (task === 'save')
                    {
                        window.processModalEditAddonMusic(element, fieldPrefix, 'edit', itemType, 'cancel', formId, idFieldId, titleFieldId);
                    }
                }

                // Show the iframe again for future modals or in case of error.
                iframe.classList.remove('visually-hidden');
            });

            // Submit button on child iframe.
            if (iframeDocument.formvalidator.isValid(iframeDocument.getElementById(formId)))
            {
                // For Save & Close (save task) when creating we need to replace the task as apply because of redirects after submit and hide the iframe.
                if (task === 'save')
                {
                    submittedTask = 'apply';
                    iframe.classList.add('visually-hidden');
                }

                iframe.contentWindow.Joomla.submitbutton(itemType.toLowerCase() + '.' + submittedTask);
            }
        }

        return false;
    };

    /**
     * Process select modal fields in child.
     *
     * @param   string  itemType     The item type (Article, Contact, etc).
     * @param   string  fieldPrefix  The fields to be updated prefix.
     * @param   string  id           The new id for the item.
     * @param   string  title        The new title for the item.
     * @param   string  catid        Future usage.
     * @param   object  object       Future usage.
     * @param   string  url          Future usage.
     * @param   string  language     Future usage.
     *
     * @return  boolean
     *
     * @since   3.7.0
     */
    window.processModalSelectAddonMusic = function(itemType, fieldPrefix, id, title, catid, object, url, language) {
        window.processModalParentAddonMusic(fieldPrefix, id, title, catid, url, language, object);

        // Close Modal only when necessary.
        if (Joomla.Modal.getCurrent())
        {
            Joomla.Modal.getCurrent().close();
        }else if(typeof jQuery('#' + modalId).data("modal") !== "undefined"){
            jQuery('#' + modalId).modal('hide');
        }

        return false;
    };

}());