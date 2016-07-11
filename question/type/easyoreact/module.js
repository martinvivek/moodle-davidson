M.qtype_easyoreact = {
    insert_easyoreact_applet: function(Y, toreplaceid, appletid, appletname,
        topnode, appleturl, feedback, readonly, appletoptions, marvinpath, moodleurl,
        stripped_answer_id) {
        var javaparams = ['mol', Y.one(topnode + ' input.mol').get(
            'value')];
        var easyoreactoptions = new Array();
        if (appletoptions) {
            easyoreactoptions[easyoreactoptions.length] = appletoptions;
        }
        if (readonly) {
            easyoreactoptions[easyoreactoptions.length] = Y.one(topnode +
                ' input.mol').get('value'); ///crl 
        }
        if (easyoreactoptions.length !== 0) {
            javaparams[javaparams.length] = "mrv"; ///added by crl
            javaparams[javaparams.length] = easyoreactoptions.join(',');
        }
        if (!this.show_java(toreplaceid, appletid, appletname,
            appleturl, 660, 460,
            'chemaxon.marvin.applet.JMSketchLaunch', javaparams,
            stripped_answer_id, marvinpath, moodleurl)) {
            this.show_error(Y, topnode);
        } else {
            var s = document.getElementById(stripped_answer_id).value;
            var inputdiv = Y.one(topnode);
            inputdiv.ancestor('form').on('submit', function() {
                Y.one(topnode + ' input.answer').set('value',
                    this.find_java_applet(appletname).getMol(
                        "cxsmiles:u"));
                var v = navigator.appVersion;
                if (v.indexOf("Win") > 0) {
                    strvalue = strvalue.split("\r\n").join("\n"); // To avoid "\r\r\n"
                } else { // Unix
                }
                Y.one(topnode + ' input.mol').set('value',
                    strvalue);
            }, this);
        }
    },
    show_error: function(Y, topnode) {
        var errormessage = '<span class ="javawarning">' + M.util.get_string(
            'enablejava', 'qtype_easyoreact') + '</span>';
        Y.one(topnode + ' .ablock').insert(errormessage, 1);
    },
    /**
     * Gets around problem in ie6 using appletname
     */
    find_java_applet: function(appletname) {
        for (appletno in document.applets) {
            if (document.applets[appletno].name == appletname) {
                return document.applets[appletno];
            }
        }
        return null;
    },
    nextappletid: 1,
    javainstalled: -99,
    doneie6focus: 0,
    doneie6focusapplets: 0,
    // Note: This method is also called from mod/audiorecorder
    show_java: function(id, appletid, appletname, java, width, height,
        appletclass, javavars, stripped_answer_id, marvinpath, moodleurl) {
        var warningspan = document.getElementById(id);
        warningspan.innerHTML = '';
        if (!this.javainstalled) {
            return false;
        }
        var newApplet = document.createElement("applet");
        newApplet.code = appletclass;
        newApplet.archive = java;
        newApplet.name = appletname;
        newApplet.width = width;
        newApplet.height = height;
        newApplet.tabIndex = -1; // Not directly tabbable
        newApplet.mayScript = true;
        newApplet.id = appletid;
        newApplet.setAttribute('codebase', marvinpath);
        var param = document.createElement('param');
        param.name = 'codebase_lookup';
        param.value = 'false';
        newApplet.appendChild(param);
        var param = document.createElement('param');
        param.name = 'menubar';
        param.value = 'false';
        newApplet.appendChild(param);
        ///on off or inChain
        var param = document.createElement('param');
        param.name = 'sketchCarbonVisibility';
        param.value = 'off';
        newApplet.appendChild(param)
        var param = document.createElement('param');
        param.name = 'implicitH';
        param.value = 'heteroterm';
        newApplet.appendChild(param);
        // In case applet supports the focushack system, we
        // pass in its id as a parameter.
        javavars[javavars.length] = 'focushackid';
        javavars[javavars.length] = newApplet.id;
        for (var i = 0; i < javavars.length; i += 2) {
            var param = document.createElement('param');
            param.appletname = javavars[i];
            param.value = javavars[i + 1];
            newApplet.appendChild(param);
        }
        var s = document.getElementById(stripped_answer_id).value;
        //s = encodeURIComponent(s);
        param.name = 'mol';
        //s = 'ccc>>';
        param.value = s;
        //console.log('s=' + s)
        warningspan.appendChild(newApplet);
        if (document.body.className.indexOf('ie6') != -1 && !this.doneie6focus) {
            var fixFocus = function() {
                if (document.activeElement && document.activeElement
                    .nodeName.toLowerCase() == 'applet') {
                    setTimeout(fixFocus, 100);
                    this.doneie6focus = 1;
                    this.doneie6focusapplets++;
                    window.focus();
                } else {
                    this.doneie6focus++;
                    if (this.doneie6focus == 2 && this.doneie6focusapplets >
                        0) {
                        // Focus one extra time after applet gets it
                        window.focus();
                    }
                    if (this.doneie6focus < 50) {
                        setTimeout(fixFocus, 100);
                    }
                }
            };
            window.arghApplets = 0;
            setTimeout(fixFocus, 100);
            this.doneie6focus = 1;
        }
        return true;
    }
}
