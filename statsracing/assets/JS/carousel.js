export default class Carousel{

    /**
     * @callback moveCallback
     * @param {number} index
     */

    /**
     * @param {HTMLElement} element 
     * @param {Object} options 
     * @param {Object} [options.slidesToScroll=1] Nb d'éléments à faire défiler
     * @param {Object} [options.slidesVisible=1] Nb d'éléments visibles dans un slide
     * @param {boolean} [options.loop=false] Doit-on boucler en fin de carousel
     */
    constructor(element, options = {}){
        this.element = element
        this.options = Object.assign({}, {
            slidesToScroll: 1,
            slidesVisible: Math.floor(this.element.parentNode.clientWidth / 240),
            loop: false
        }, options)
        let children = [].slice.call(element.children)
        this.isMobile = false
        this.currentItem = 0
        this.moveCallbacks = []
        
        // Modification du DOM
        this.root = this.createDivWithClass('carousel')
        this.root.setAttribute('tabindex', '0')
        this.container = this.createDivWithClass('carousel_container')
        this.root.appendChild(this.container)
        this.element.appendChild(this.root)
        this.items = children.map((child) => {
            let item = this.createDivWithClass('carousel_item')
            item.appendChild(child)
            this.container.appendChild(item)
            return item
        })
        this.setStyle()
        this.createNavigation()

        // Événements
        this.moveCallbacks.forEach(cb => cb(0))
        window.addEventListener('load', this.onWindowResize.bind(this))
        window.addEventListener('resize', this.onWindowResize.bind(this))
        this.root.addEventListener('keyup', (e) => {
            if(e.key === 'ArrowRight' || e.key === 'Right'){ this.next() }
            else if(e.key === 'ArrowLeft' || e.key === 'Left'){ this.prev() }
        })
    }

    /**
     * @param {string} className 
     * @returns {HTMLElement}
     */
    createDivWithClass(className){
        let div = document.createElement('div')
        div.setAttribute('class', className)
        return div
    }

    /**
     * Applique les bonnes dimensions aux éléments du carousel.
     */
    setStyle() {
        let ratio = this.items.length / this.slidesVisible
        this.container.style.width = (ratio * 100) + "%"
        this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + "%")
    }

    createNavigation(){
        let nextButton = this.createDivWithClass('carousel_next')
        let prevButton = this.createDivWithClass('carousel_prev')
        this.root.appendChild(nextButton)
        this.root.appendChild(prevButton)
        nextButton.addEventListener('click', this.next.bind(this))
        prevButton.addEventListener('click', this.prev.bind(this))
        if(this.options.loop === true){
            return
        }
        this.onMove(index => {
            if(index === 0){
                prevButton.classList.add('carousel_prev_hidden')
            } else {
                prevButton.classList.remove('carousel_prev_hidden')
            }
            if (this.items[this.currentItem + this.slidesVisible] === undefined){
                nextButton.classList.add('carousel_next_hidden')
            } else {
                nextButton.classList.remove('carousel_next_hidden')
            }
        })
    }

    next(){
        this.goToItem(this.currentItem + this.slidesToScroll)
    }

    prev() {
        this.goToItem(this.currentItem - this.slidesToScroll)
    }

    /**
     * Déplace le carousel vers l'élément ciblé
     * @param {number} index 
     */
    goToItem(index){
        if(index < 0){
            if(this.options.loop) {
                index = this.items.length - this.slidesVisible
            } else {
                return
            }
        } else if(index >= this.items.length || (this.items[this.currentItem + this.slidesVisible] === undefined && index > this.currentItem)){
            if(this.options.loop) {
                index = 0
            } else {
                return
            }
        }
        let translateX = index * -100 / this.items.length
        this.container.style.transform = 'translate3d(' + translateX + '%, 0, 0)'
        this.currentItem = index
        this.moveCallbacks.forEach(cb => cb(index))
    }

    /**
     * @param {moveCallback} cb 
     */
    onMove(cb) {
        this.moveCallbacks.push(cb)
    }

    onWindowResize(){
        let ratio = Math.floor(this.element.parentNode.clientWidth / 240);
        this.options.slidesVisible = ratio
        this.setStyle()
        this.moveCallbacks.forEach(cb => cb(this.currentItem))
    }

    /**
     * @returns {number}
     */
    get slidesToScroll() {
        return this.isMobile ? 1 : this.options.slidesToScroll
    }

    /**
     * @returns {number}
     */
    get slidesVisible() {
        return this.isMobile ? 1 : this.options.slidesVisible
    }

}