<!-- Edit Habit Modal -->
<div class="modal" id="edit-habit-modal" aria-hidden="true">

    <div class="modal-backdrop" data-close-edit-modal></div>

    <div class="modal-dialog" role="dialog" aria-modal="true">
        <div class="modal-header">
            <div>
                <h2>Edit Habit</h2>
                <p>Update your habit details.</p>
            </div>

            <button
                type="button"
                class="modal-close"
                aria-label="Close"
                data-close-edit-modal
            >
                &times;
            </button>
        </div>

        <form
            class="habit-form"
            id="edit-habit-form"
            method="POST"
        >
            <input
                type="hidden"
                id="edit-habit-id"
                name="habit_id"
            >

            <div class="form-group">
                <label for="edit-habit-name">Habit Name</label>
                <input
                    type="text"
                    id="edit-habit-name"
                    name="habit_name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="edit-habit-description">Description (Optional)</label>
                <textarea
                    id="edit-habit-description"
                    name="description"
                    rows="3"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="edit-habit-category">Category</label>
                <select id="edit-habit-category" name="category" required>
                    <option value="health">Health</option>
                    <option value="fitness">Fitness</option>
                    <option value="learning">Learning</option>
                    <option value="productivity">Productivity</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="edit-habit-frequency">Frequency</label>
                <select id="edit-habit-frequency" name="frequency" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-edit-modal
                >
                    Cancel
                </button>

                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>