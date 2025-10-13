import {Controller} from '@hotwired/stimulus';
import {computePosition} from '@floating-ui/dom'

/* stimulusFetch: 'lazy' */
/**
 * @class DropdownController
 * @extends Controller
 * @description A controller for creating dropdowns.
 *
 * @example
 * ```html
 * <div data-controller="dropdown" class="relative">
 *   <button data-dropdown-target="trigger">Toggle</button>
 *   <menu data-dropdown-target="popover" class="hidden absolute mt-2 right-0" data-placement="bottom-left">
 *     <li><a href="#">Item 1</a></li>
 *     <li><a href="#">Item 2</a></li>
 *     <li><a href="#">Item 3</a></li>
 *   </menu>
 * </div>
 * ```
 */
export default class extends Controller {
	static targets = ['trigger', 'popover'];

	isOpen = false;

	initialize() {
		this.toggle = this.toggle.bind(this);
		this.handleClick = this.handleClick.bind(this);
	}

	connect() {
		this.hasTriggerTarget && this.triggerTarget.addEventListener('click', this.toggle);
		document.addEventListener('click', this.handleClick);
	}

	disconnect() {
		this.hasTriggerTarget && this.triggerTarget.removeEventListener('click', this.toggle);
	}

	toggle() {
		this.isOpen ? this.close() : this.open();
	}

	open() {
		this.popoverTarget.classList.toggle('hidden', false);
		const placement = this.popoverTarget.dataset.placement || 'bottom-end';

		computePosition(this.hasTriggerTarget ? this.triggerTarget : this.element, this.popoverTarget, {placement})
			.then(({x, y}) => {
				Object.assign(this.popoverTarget.style, {
					top: `${y}px`,
					left: `${x}px`
				});
			});

		this.isOpen = true;
	}

	close() {
		this.popoverTarget.classList.toggle('hidden', true);
		this.isOpen = false;
	}

	handleClick(event) {
		const shouldClose = this.isOpen && !this.element.contains(event.target);

		if (shouldClose) {
			this.close();
		}
	}
}
