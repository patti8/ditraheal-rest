require "test_helper"

class ReferencesControllerTest < ActionDispatch::IntegrationTest
  setup do
    @reference = references(:one)
  end

  test "should get index" do
    get references_url, as: :json
    assert_response :success
  end

  test "should create reference" do
    assert_difference("Reference.count") do
      post references_url, params: { reference: { deskripsi: @reference.deskripsi, jenis: @reference.jenis } }, as: :json
    end

    assert_response :created
  end

  test "should show reference" do
    get reference_url(@reference), as: :json
    assert_response :success
  end

  test "should update reference" do
    patch reference_url(@reference), params: { reference: { deskripsi: @reference.deskripsi, jenis: @reference.jenis } }, as: :json
    assert_response :success
  end

  test "should destroy reference" do
    assert_difference("Reference.count", -1) do
      delete reference_url(@reference), as: :json
    end

    assert_response :no_content
  end
end
