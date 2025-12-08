import { Controller } from '@hotwired/stimulus';
import Sortable from 'sortablejs';

export default class extends Controller {
    static values = {
        status: String
    }

    connect() {
        this.sortable = new Sortable(this.element, {
            group: 'shared',
            animation: 150,
            draggable: '.postit-item',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onStart: () => {
                window.isDragging = true;
            },
            onEnd: (event) => {
                this.onDragEnd(event);
            }
        });
    }

    async onDragEnd(event) {
        const postItId = event.item.dataset.postItId;
        const newStatus = event.to.dataset.sortableStatusValue;
        const oldStatus = event.from.dataset.sortableStatusValue;

        if (oldStatus === newStatus) {
            setTimeout(() => { window.isDragging = false; }, 100);
            return;
        }

        try {
            const response = await fetch(`/post_it/${postItId}/update-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            });

            if (!response.ok) throw new Error('Update failed');

            await response.json();

            const card = event.item.querySelector('.post-it-card');
            if (card) {
                card.classList.remove('post-it-pending', 'post-it-todo', 'post-it-ongoing', 'post-it-finished');
                const statusMap = {
                    'PENDING': 'post-it-pending',
                    'TO DO': 'post-it-todo',
                    'ON GOING': 'post-it-ongoing',
                    'FINISHED': 'post-it-finished'
                };
                if (statusMap[newStatus]) {
                    card.classList.add(statusMap[newStatus]);
                }
            }

        } catch (error) {
            event.to.removeChild(event.item);
            event.from.insertBefore(event.item, event.from.children[event.oldIndex] || null);
            alert('Failed to update status');
        } finally {
            setTimeout(() => { window.isDragging = false; }, 100);
        }
    }

    disconnect() {
        if (this.sortable) {
            this.sortable.destroy();
        }
    }
}

