<!-- Add Habit Modal -->
<div class="modal" id="habit-modal" aria-hidden="true">

    <div class="modal-backdrop" data-close-modal></div>

    <div class="modal-dialog" role="dialog" aria-modal="true">
        <div class="modal-header">
            <div>
                <h2>Add Habit</h2>
                <p>Create a habit you want to track.</p>
            </div>

            <button
                type="button"
                class="modal-close"
                aria-label="Close"
                data-close-modal
            >
                &times;
            </button>
        </div>

        <form
            class="habit-form"
            id="habit-form"
            action="create-habit.php"
            method="POST"
        >
            <div class="form-group">
                <label for="habit-name">Habit Name</label>
                <input
                    type="text"
                    id="habit-name"
                    name="habit_name"
                    placeholder="e.g. Morning Walk"
                    required
                >
            </div>

            <div class="form-group">
                <label for="habit-description">Description (Optional)</label>
                <textarea
                    id="habit-description"
                    name="description"
                    rows="3"
                    placeholder="Describe your habit..."
                ></textarea>
            </div>

            <div class="form-group">
                <label for="habit-category">Category</label>
                <select id="habit-category" name="category" required>
                    <option value="">Select category</option>
                    <option value="health">Health</option>
                    <option value="fitness">Fitness</option>
                    <option value="learning">Learning</option>
                    <option value="productivity">Productivity</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="habit-frequency">Frequency</label>
                <select id="habit-frequency" name="frequency" required>
                    <option value="">Select frequency</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-modal
                >
                    Cancel
                </button>

                <button type="submit" class="btn btn-primary">
                    Create Habit
                </button>
            </div>
        </form>
    </div>

</div>