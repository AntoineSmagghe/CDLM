/**
 *  Listen an icon and open a menu.
 *
 * @class openMiniMenu
 */

class openMiniMenu {
    constructor(idListen, idToShow, howToDisplay, background, animation = null) {
        this.idListen = idListen;
        this.idToShow = idToShow;
        this.howToDisplay = howToDisplay;
        this.background = background;
        this.animation = animation;
        this.menuIsOpen = false;

    }

    listenIco() {
        this.idListen.addEventListener("click", () => {
            this.idListen.style.visibility = "hidden";
            this.idListen.style.animation = "";
            this.idToShow.style.display = this.howToDisplay;
            this.menuIsOpen = true;
            this.closeMenu();
        });
    }

    hideMenuWhenScroll(listenEvent) {
        window.removeEventListener(listenEvent, this.hideMenuWhenScroll);
    }

    closeMenu() {
        window.addEventListener("click", (e) => {
            if (e.target === this.background){
                this.idToShow.style.display = "none";
                this.idListen.style.visibility = "visible";
            }
        });

        if (this.isMobile()) {
            window.addEventListener("scroll", () => {
                this.idToShow.style.display = "none";
                this.idListen.style.visibility = "visible";
            });
        }
    }

    isMobile() {
        let whatUser = navigator.userAgent;
        if (whatUser.match(/Android/i)
            || whatUser.match(/Iphone/i)
            || whatUser.match(/webOS/i)
            || whatUser.match(/Ipad/i)
            || whatUser.match(/Ipod/i)
            || whatUser.match(/BlackBerry/i)
            || whatUser.match(/Windows Phone/i)) {
            return true;
        } else {
            return false;
        }
    }
}