<div class="modal" id="delete-habit-modal" aria-hidden="true">

    <div class="modal-backdrop" data-close-delete-modal></div>

    <div class="modal-dialog" role="dialog" aria-modal="true">

        <div class="modal-header">
            <div>
                <h2>Delete Habit</h2>
                <p>Are you sure you want to delete this habit?</p>
            </div>

            <button
                type="button"
                class="modal-close"
                data-close-delete-modal
            >
                &times;
            </button>
        </div>

        <form id="delete-habit-form" method="POST" action="delete-habit.php">

            <input
                type="hidden"
                id="delete-habit-id"
                name="habit_id"
            >

            <div class="modal-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-delete-modal
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Delete
                </button>

            </div>

        </form>

    </div>
</div>